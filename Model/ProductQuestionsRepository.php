<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchResultsFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Tingle\Pmeds\Api\ProductQuestionsRepositoryInterface;
use Tingle\Pmeds\Model\QuestionsFactory as ModelFactory;
use Tingle\Pmeds\Model\ResourceModel\ProductQuestions as ResourceModel;
use Tingle\Pmeds\Model\ResourceModel\ProductQuestions\CollectionFactory;
use Tingle\Pmeds\Api\Data\ProductQuestionsInterface as Config;
use Tingle\Pmeds\Api\Data\QuestionsInterface;
use Tingle\Pmeds\Api\QuestionsRepositoryInterface;


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
     * @var CollectionFactory
     */
    private $collectionFactory;

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

    private $questionsRepository;

    /**
     * QuestionsRepository constructor.
     *
     * @param QuestionsFactory $modelFactory
     * @param ResourceModel $resource
     * @param CollectionFactory $collectionFactory
     * @param SearchResultsFactory $searchResultsFactory
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     * @param QuestionsRepositoryInterface $questionsRepository
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resource,
        CollectionFactory $collectionFactory,
        SearchResultsFactory $searchResultsFactory,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder,
        QuestionsRepositoryInterface $questionsRepository
    ) {
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
        $this->questionsRepository = $questionsRepository;
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
        $collection = $this->collectionFactory->create();

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
        $productIdFilter = $this->filterBuilder->setField(Config::FIELD_PRODUCT_ID)->setValue($product->getId())->create();
        $storeIdFilter = $this->filterBuilder->setField(Config::FIELD_STORE_ID)->setValue($product->getStoreId())->create();
        $searchCriteria = $this->searchCriteriaBuilder->addFilters([$productIdFilter,$storeIdFilter])->create();

        return $this->getList($searchCriteria);
    }

    /**
     * @inheritdoc
     */
    public function getAllProductQuestions($product)
    {
        $productQuestionsMetaData = $this->getAllProductQuestionsMetaData($product);

        $filters = [];

        /** @var \Tingle\Pmeds\Api\Data\ProductQuestionsInterface $metadata */
        foreach ($productQuestionsMetaData as $metadata) {
            $filters[] = $this->filterBuilder->setField(QuestionsInterface::FIELD_ID)
                ->setValue($metadata->getSelectedQuestionId())
                ->create();
        }

        $searchCriteria = $this->searchCriteriaBuilder->addFilters($filters)->create();

        return $this->questionsRepository->getList($searchCriteria);
    }
}
