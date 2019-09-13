<?php declare(strict_types=1);
namespace Tingle\Pmeds\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Message\Manager;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Tingle\Pmeds\Api\ProductQuestionsRepositoryInterface;
use Tingle\Pmeds\Api\Data\ProductQuestionsInterfaceFactory;
use Tingle\Pmeds\Model\ResourceModel\ProductQuestions\CollectionFactory;

class SaveProductQuestions implements ObserverInterface
{
    /**
     * @var ProductQuestionsInterfaceFactory
     */
    private $productQuestionsFactory;

    /**
     * @var ProductQuestionsRepositoryInterface
     */
    private $productQuestionsRepository;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var Manager
     */
    private $messageManager;

    /**
     * @var null|string
     */
    private $questionnaireIntro;

    /**
     * @var null|array
     */
    private $questionsList;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $product;

    /**
     * SaveProductQuestions constructor.
     *
     * @param ProductQuestionsRepositoryInterface $productQuestionsRepository
     * @param ProductQuestionsInterfaceFactory $productQuestionsFactory
     * @param CollectionFactory $collectionFactory
     * @param Manager $messageManager
     */
    public function __construct(
        ProductQuestionsRepositoryInterface $productQuestionsRepository,
        ProductQuestionsInterfaceFactory $productQuestionsFactory,
        CollectionFactory $collectionFactory,
        Manager $messageManager
    ) {
        $this->productQuestionsRepository = $productQuestionsRepository;
        $this->productQuestionsFactory = $productQuestionsFactory;
        $this->collectionFactory = $collectionFactory;
        $this->messageManager = $messageManager;
    }

    /**
     * Save product questions metadata
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        try {
            /** @var \Magento\Catalog\Model\Product $product */
            $product = $observer->getProduct();

            $this->product = $product;
            $this->questionnaireIntro = $product->getData(ConfigInterface::QUESTIONNAIRE_INTRO_TEXT);
            $this->questionsList = $product->getData(ConfigInterface::SELECTED_QUESTIONS_LIST);

            if (!$this->questionnaireIntro && !$this->questionsList) {
                /** Pmeds tab has no data provided by user. Stop execution. */
                return $this;
            }

            $this->removePreviouslySelectedProductQuestions();

            if (!empty($this->questionsList)) {
                $this->saveNewSelectedProductQuestions();
            }

        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $this;
    }

    /**
     * Remove (un-select) previously selected product questions
     *
     * @throws \Exception
     */
    private function removePreviouslySelectedProductQuestions()
    {
        $productQuestions = $this->productQuestionsRepository->getAllProductQuestionsMetaData($this->product);

        /** @var \Tingle\Pmeds\Api\Data\ProductQuestionsInterface $productQuestion */
        foreach ($productQuestions as $productQuestion) {
            $this->productQuestionsRepository->delete($productQuestion);
        }
    }

    /**
     * Save selected questions for the product
     *
     * @throws \Exception
     */
    private function saveNewSelectedProductQuestions()
    {
        foreach ($this->questionsList as $questionId) {
            /** @var \Tingle\Pmeds\Model\ProductQuestions $model */
            $model = $this->productQuestionsFactory->create();
            $model->setProductId($this->product->getId())
                ->setStoreId($this->product->getStoreId())
                ->setSelectedQuestionId($questionId);

            $this->productQuestionsRepository->save($model);
        }
    }
}
