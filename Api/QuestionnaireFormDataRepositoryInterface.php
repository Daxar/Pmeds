<?php
namespace Tingle\Pmeds\Api;

interface QuestionnaireFormDataRepositoryInterface
{
    public function getByOrderId($orderId);

    public function save($model);

    public function delete($model);
}
