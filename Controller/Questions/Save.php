<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Questions;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterfaceFactory;
use Tingle\Pmeds\Api\QuestionnaireFormDataRepositoryInterface;

class Save extends Action
{
    private $cartRepository;

    private $quoteIdMaskFactory;

    private $orderRepository;

    private $searchCriteriaBuilder;

    private $questionnaireFormDataFactory;

    private $questionnaireFormDataRepository;

    public function __construct(
        Context $context,
        CartRepositoryInterface $cartRepository,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        QuestionnaireFormDataInterfaceFactory $questionnaireFormDataFactory,
        QuestionnaireFormDataRepositoryInterface $questionnaireFormDataRepository
    ) {
        parent::__construct($context);
        $this->cartRepository = $cartRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->questionnaireFormDataFactory = $questionnaireFormDataFactory;
        $this->questionnaireFormDataRepository = $questionnaireFormDataRepository;
    }

    public function execute()
    {
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);

        if (!$this->getRequest()->isAjax() || !$this->getRequest()->isPost()) {
            $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            return $redirect->setPath('');
        }

        try {
            $this->storeQuestionnaireData();

            $resultJson->setData(['success' => true]);
        } catch (\Exception $e) {
            $resultJson->setData(['success' => false]);
        }

        return $resultJson;
    }

    private function storeQuestionnaireData()
    {
        $data = $this->getRequest()->getPostValue();

        $maskedId = $data['masked_cart_id'];
        unset($data['masked_cart_id']);

        /** @var \Magento\Quote\Model\QuoteIdMask $quoteIdMask */
        $quoteIdMask = $this->quoteIdMaskFactory->create()->load($maskedId, 'masked_id');
        $searchCriteria = $this->searchCriteriaBuilder->addFilter('quote_id', $quoteIdMask->getQuoteId(), 'eq')->create();

        /** @var \Magento\Sales\Model\Order $order */
        $order = $this->orderRepository->getList($searchCriteria)->getFirstItem();

        /** @var \Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterface $model */
        $model = $this->questionnaireFormDataFactory->create();
        $model->setOrderId($order->getId())
            ->setQuestionnaireFormData($data);

        $this->questionnaireFormDataRepository->save($model);
    }
}
