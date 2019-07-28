<?php
namespace Tingle\Pmeds\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Tingle\Pmeds\Api\Data\QuestionsInterface;

interface QuestionsRepositoryInterface
{
    /**
     * Get question from persistent storage by id
     *
     * @param integer $id
     * @return \Tingle\Pmeds\Api\Data\QuestionsInterface
     */
    public function getById($id);

    /**
     * Delete question from persistent storage
     *
     * @param \Tingle\Pmeds\Api\Data\QuestionsInterface $model
     * @return true|\Exception
     */
    public function delete($model);

    /**
     * Save question to the persistent storage
     *
     * @param \Tingle\Pmeds\Api\Data\QuestionsInterface $model
     * @return \Tingle\Pmeds\Api\Data\QuestionsInterface
     */
    public function save($model);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return array TODO:: Return type
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
