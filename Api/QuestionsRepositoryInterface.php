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
     * @throws \Exception
     */
    public function getById($id);

    /**
     * Delete question from persistent storage
     *
     * @param \Tingle\Pmeds\Api\Data\QuestionsInterface $model
     * @return true
     * @throws \Exception
     */
    public function delete($model);

    /**
     * Save question to the persistent storage
     *
     * @param \Tingle\Pmeds\Api\Data\QuestionsInterface $model
     * @return \Tingle\Pmeds\Api\Data\QuestionsInterface
     * @throws \Exception
     */
    public function save($model);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
