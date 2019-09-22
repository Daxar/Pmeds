<?php
namespace Tingle\Pmeds\Api;

use Magento\Framework\Exception\NoSuchEntityException;

interface QuestionnaireFormDataRepositoryInterface
{
    /**
     * @param integer $orderId
     * @return \Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterface
     * @throws NoSuchEntityException
     */
    public function getByOrderId($orderId);

    /**
     * @param \Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterface $model
     * @return \Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterface
     */
    public function save($model);

    /**
     * @param \Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterface $model
     * @return boolean
     * @throws NoSuchEntityException
     */
    public function delete($model);
}
