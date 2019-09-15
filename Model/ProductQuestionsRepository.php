<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Data\Collection;
use Tingle\Pmeds\Api\ProductQuestionsRepositoryInterface;
use Tingle\Pmeds\Api\Data\ProductQuestionsInterface as Config;
use Tingle\Pmeds\Api\Data\QuestionsInterface as QuestionConfig;
use Tingle\Pmeds\Model\QuestionsFactory as ModelFactory;
use Tingle\Pmeds\Model\ResourceModel\ProductQuestions as ResourceModel;

class ProductQuestionsRepository implements ProductQuestionsRepositoryInterface
{
    /**
     * @var QuestionsFactory
     */
    private $modelFactory;

    /**
     * @var ResourceModel
     */
    private $resource;

    /**
     * @var \Tingle\Pmeds\Model\ResourceModel\ProductQuestions\CollectionFactory
     */
    private $productQuestionsCollectionFactory;

    /**
     * @var SearchResultsFactory
     */
    private $searchResultsFactory;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var \Tingle\Pmeds\Model\ResourceModel\Questions\CollectionFactory
     */
    private $questionsCollectionFactory;

    /**
     * ProductQuestionsRepository constructor.
     *
     * @param QuestionsFactory $modelFactory
     * @param ResourceModel $resource
     * @param \Tingle\Pmeds\Model\ResourceModel\ProductQuestions\CollectionFactory $productQuestionsCollectionFactory
     * @param SearchResultsFactory $searchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param \Tingle\Pmeds\Model\ResourceModel\Questions\CollectionFactory $questionsCollectionFactory
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resource,
        \Tingle\Pmeds\Model\ResourceModel\ProductQuestions\CollectionFactory $productQuestionsCollectionFactory,
        SearchResultsFactory $searchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        \Tingle\Pmeds\Model\ResourceModel\Questions\CollectionFactory $questionsCollectionFactory
    ) {
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->productQuestionsCollectionFactory = $productQuestionsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->questionsCollectionFactory = $questionsCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        /** @var \Tingle\Pmeds\Model\ProductQuestions $model */
        $model = $this->modelFactory->create();
        $this->resource->load($model, $id, Config::FIELD_ID);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Product question with "%1" ID doesn\'t exist.', $id));
        }
        return $model;
    }

    /**
     * @inheritDoc
     */
    public function save($model)
    {
        try {
            $this->resource->save($model);
            return $model;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function delete($model)
    {
        try {
            $this->resource->delete($model);
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Tingle\Pmeds\Model\ResourceModel\ProductQuestions\Collection $collection */
        $collection = $this->productQuestionsCollectionFactory->create();

        /** @var \Magento\Framework\Api\SearchResults $searchResult */
        $searchResult = $this->searchResultsFactory->create();
        $searchResult->setSearchCriteria($searchCriteria);
        $searchResult->setItems($collection->getItems());
        $searchResult->setTotalCount($collection->getSize());
        return $searchResult->getItems();
    }

    /**
     * @inheritdoc
     */
    public function getAllProductQuestionsMetaData($product)
    {
        $productIdFilter = $this->filterBuilder->setField(Config::FIELD_PRODUCT_ID)->setValue($product->getId())->setConditionType('eq')->create();
        $storeIdFilter = $this->filterBuilder->setField(Config::FIELD_STORE_ID)->setValue($product->getStoreId())->setConditionType('eq')->create();
        $searchCriteriaBuilder = $this->searchCriteriaBuilder;
        $searchCriteria = $searchCriteriaBuilder->addFilters([$productIdFilter, $storeIdFilter])->create();

        return $this->getList($searchCriteria);
    }

    /**
     * @inheritdoc
     */
    public function getAllProductQuestions($product)
    {
        $productQuestionsMetaData = $this->getAllProductQuestionsMetaData($product);

        /** @var \Tingle\Pmeds\Model\ResourceModel\Questions\Collection $collection */
        $collection = $this->questionsCollectionFactory->create();


        $ids = [];
        /** @var \Tingle\Pmeds\Api\Data\ProductQuestionsInterface $productQuestion */
        foreach ($productQuestionsMetaData as $productQuestion) {
            $ids[] = $productQuestion->getSelectedQuestionId();
        }

        $collection->addFieldToFilter(QuestionConfig::FIELD_ID, ['in' => $ids]);
        $collection->setOrder(QuestionConfig::FIELD_SORT_ORDER, Collection::SORT_ORDER_ASC);

        return $collection;
    }
}
