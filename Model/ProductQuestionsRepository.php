<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Magento\Framework\Api\Search\FilterGroupBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
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
use Magento\Framework\Api\SortOrder;

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
     * @var FilterGroupBuilder
     */
    private $filterGroupBuilder;

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
     * @param FilterGroupBuilder $filterGroupBuilder
     * @param \Tingle\Pmeds\Model\ResourceModel\Questions\CollectionFactory $questionsCollectionFactory
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resource,
        \Tingle\Pmeds\Model\ResourceModel\ProductQuestions\CollectionFactory $productQuestionsCollectionFactory,
        SearchResultsFactory $searchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        FilterGroupBuilder $filterGroupBuilder,
        \Tingle\Pmeds\Model\ResourceModel\Questions\CollectionFactory $questionsCollectionFactory
    ) {
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->productQuestionsCollectionFactory = $productQuestionsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->filterGroupBuilder = $filterGroupBuilder;
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
        /** @var SearchResults $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var \Tingle\Pmeds\Model\ResourceModel\ProductQuestions\Collection $collection */
        $collection = $this->productQuestionsCollectionFactory->create();

        foreach ($searchCriteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrdersData = $searchCriteria->getSortOrders();
        if ($sortOrdersData) {
            foreach ($sortOrdersData as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($searchCriteria->getCurrentPage());

        $collection->setPageSize($searchCriteria->getPageSize());

        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * @inheritdoc
     */
    public function getAllProductQuestionsMetaData($product)
    {
        $productIdFilter = $this->filterBuilder->setField(Config::FIELD_PRODUCT_ID)->setValue($product->getId())->setConditionType('eq')->create();
        $productIdFilterGroup = $this->filterGroupBuilder->setFilters([$productIdFilter])->create();

        $storeIdFilter = $this->filterBuilder->setField(Config::FIELD_STORE_ID)->setValue($product->getStoreId())->setConditionType('eq')->create();
        $storeIdFilterGroup = $this->filterGroupBuilder->setFilters([$storeIdFilter])->create();

        $searchCriteria = $this->searchCriteriaBuilder->setFilterGroups([$productIdFilterGroup, $storeIdFilterGroup])->create();

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
        foreach ($productQuestionsMetaData->getItems() as $productQuestion) {
            $ids[] = $productQuestion->getSelectedQuestionId();
        }

        $collection->addFieldToFilter(QuestionConfig::FIELD_ID, ['in' => $ids]);
        $collection->setOrder(QuestionConfig::FIELD_SORT_ORDER, Collection::SORT_ORDER_ASC);

        return $collection;
    }
}
