<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Questions;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Quote\Model\QuoteIdMaskFactory;
use Magento\Sales\Api\OrderRepositoryInterface;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterfaceFactory;
use Tingle\Pmeds\Api\QuestionnaireFormDataRepositoryInterface;
use Magento\Framework\HTTP\PhpEnvironment\RemoteAddress;
use Tingle\Pmeds\Api\QuestionsRepositoryInterface;
use Tingle\Pmeds\Block\Form\Fields\Select;

class Save extends Action
{
    private $cartRepository;

    private $quoteIdMaskFactory;

    private $orderRepository;

    private $searchCriteriaBuilder;

    private $questionnaireFormDataFactory;

    private $questionnaireFormDataRepository;

    /**
     * @var QuestionsRepositoryInterface
     */
    private $questionsRepository;

    private $config;

    private $remoteAddress;

    public function __construct(
        Context $context,
        CartRepositoryInterface $cartRepository,
        QuoteIdMaskFactory $quoteIdMaskFactory,
        OrderRepositoryInterface $orderRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        QuestionnaireFormDataInterfaceFactory $questionnaireFormDataFactory,
        QuestionnaireFormDataRepositoryInterface $questionnaireFormDataRepository,
        QuestionsRepositoryInterface $questionsRepository,
        ConfigInterface $config,
        RemoteAddress $remoteAddress
    ) {
        parent::__construct($context);
        $this->cartRepository = $cartRepository;
        $this->quoteIdMaskFactory = $quoteIdMaskFactory;
        $this->orderRepository = $orderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->questionnaireFormDataFactory = $questionnaireFormDataFactory;
        $this->questionnaireFormDataRepository = $questionnaireFormDataRepository;
        $this->questionsRepository = $questionsRepository;
        $this->config = $config;
        $this->remoteAddress = $remoteAddress;
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
            ->setCustomerIdAddress($this->remoteAddress->getRemoteAddress())
            ->setQuestionnaireFormData($this->formatData($data, $order));

        $this->questionnaireFormDataRepository->save($model);
    }

    /**
     * @param array $data
     * @param \Magento\Sales\Model\Order $order
     * @return array
     */
    private function formatData($data, $order)
    {
        $formattedData = [];

        foreach ($data as $sku => $item) {
            $formattedItems = [];

            foreach ($item as $questionMetadata) {
                $questionModel = $this->questionsRepository->getById($questionMetadata['question_id']);

                $customerAnswer = $questionMetadata['customer_answer'];
                $correctAnswer = null;

                if ($this->config->getType($questionModel->getTypeId()) === Select::TYPE) {
                    $options = json_decode($questionModel->getOptions(), true);
                    foreach ($options as $option) {
                        if ($option['record_id'] == $customerAnswer) {
                            $customerAnswer = $option['row_name'];
                        }
                        if ($option['record_id'] == $questionModel->getAnswer()) {
                            $correctAnswer = $option['row_name'];
                        }
                    }
                }

                $formattedItems[] = [
                    'question_id' => $questionModel->getId(),
                    'question_title' => $questionModel->getTitle(),
                    'question_type' => $this->config->getType($questionModel->getTypeId()),
                    'is_required' => $questionModel->getRequired() ? __('Yes') : __('No') ,
                    'customer_answer' => $customerAnswer,
                    'correct_answer' => $correctAnswer
                ];
            }

            $formattedData[] = [
                'timestamp' => $item[0]['timestamp'],
                'product_name' => $this->getProductName($sku, $order),
                'product_sku' => $sku,
                'questions' => $formattedItems
            ];
        }

        return $formattedData;
    }

    /**
     * @param string $sku
     * @param \Magento\Sales\Model\Order $order
     * @return string
     */
    private function getProductName($sku, $order)
    {
        foreach ($order->getAllItems() as $item) {
            if ($item->getSku() === $sku) {
                return $item->getName();
            }
        }

        return '';
    }
}
