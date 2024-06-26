<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Magento\Framework\Exception\NoSuchEntityException;
use Tingle\Pmeds\Api\QuestionnaireFormDataRepositoryInterface;
use Tingle\Pmeds\Model\QuestionnaireFormDataFactory;
use Tingle\Pmeds\Model\ResourceModel\QuestionnaireFormData as ResourceModel;
use Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterface as Config;

class QuestionnaireFormDataRepository implements QuestionnaireFormDataRepositoryInterface
{
    /**
     * @var \Tingle\Pmeds\Model\QuestionnaireFormDataFactory
     */
    private $modelFactory;

    /**
     * @var ResourceModel
     */
    private $resource;

    /**
     * QuestionnaireFormDataRepository constructor.
     *
     * @param \Tingle\Pmeds\Model\QuestionnaireFormDataFactory $modelFactory
     * @param ResourceModel $resource
     */
    public function __construct(QuestionnaireFormDataFactory $modelFactory, ResourceModel $resource)
    {
        $this->modelFactory = $modelFactory;
        $this->resource = $resource;
    }

    /**
     * @inheritDoc
     */
    public function getByOrderId($orderId)
    {
        /** @var \Tingle\Pmeds\Model\QuestionnaireFormData $model */
        $model = $this->modelFactory->create();
        $this->resource->load($model, $orderId, Config::FIELD_ORDER_ID);
        if (!$model->getId()) {
            throw new NoSuchEntityException(__('Form data with "%1" ID doesn\'t exist.', $orderId));
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
}
