<?php
namespace Tingle\Pmeds\Api;

interface QuestionnaireFormDataRepositoryInterface
{
    public function getById($id);

    public function save($model);

    public function delete($model);
}
