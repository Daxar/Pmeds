<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;
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
     * QuestionsRepository constructor.
     * @param QuestionsFactory $modelFactory
     * @param ResourceModel $resource
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        ModelFactory $modelFactory,
        ResourceModel $resource,
        CollectionFactory $collectionFactory
    ) {
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
        $this->collectionFactory = $collectionFactory;
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
        //TODO: Implement it
    }
}
