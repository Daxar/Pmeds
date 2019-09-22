<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchResultsFactory;
use Tingle\Pmeds\Api\QuestionsRepositoryInterface;
use Tingle\Pmeds\Model\QuestionsFactory as ModelFactory;
use Tingle\Pmeds\Model\ResourceModel\Questions as ResourceModel;
use Tingle\Pmeds\Model\ResourceModel\Questions\CollectionFactory;
use Tingle\Pmeds\Api\Data\QuestionsInterface as Config;

class QuestionsRepository implements QuestionsRepositoryInterface
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
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * @var SearchResultsFactory
     */
    private $searchResultsFactory;

    /**
     * QuestionsRepository constructor.
     * @param QuestionsFactory $modelFactory
     * @param ResourceModel $resource
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsFactory $searchResultsFactory
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resource,
        CollectionFactory $collectionFactory,
        SearchResultsFactory $searchResultsFactory
    ) {
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        /** @var \Tingle\Pmeds\Model\Questions $model */
        $model = $this->modelFactory->create();
        $this->resource->load($model, $id, Config::FIELD_ID);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Question with "%1" ID doesn\'t exist.', $id));
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

        /** @var \Tingle\Pmeds\Model\ResourceModel\Questions\Collection $collection */
        $collection = $this->collectionFactory->create();

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
}
