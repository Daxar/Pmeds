<?php
namespace Tingle\Pmeds\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductQuestionsRepositoryInterface
{
    /**
     * Get question from persistent storage by id
     *
     * @param integer $id
     * @return \Tingle\Pmeds\Api\Data\ProductQuestionsInterface
     * @throws \Exception
     */
    public function getById($id);

    /**
     * Delete question from persistent storage
     *
     * @param \Tingle\Pmeds\Api\Data\ProductQuestionsInterface $model
     * @return true
     * @throws \Exception
     */
    public function delete($model);

    /**
     * Save question to the persistent storage
     *
     * @param \Tingle\Pmeds\Api\Data\ProductQuestionsInterface $model
     * @return \Tingle\Pmeds\Api\Data\ProductQuestionsInterface
     * @throws \Exception
     */
    public function save($model);

    /**
     * Get list
     *
     * @param SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

    /**
     * Get collection of product questions metadata
     * Returns collection of \Tingle\Pmeds\Api\Data\ProductQuestionsInterface models
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Magento\Framework\Api\SearchResultsInterface
     */
    public function getAllProductQuestionsMetaData($product);

    /**
     * Get collection of question models for provided product
     * Return collection of \Tingle\Pmeds\Api\Data\QuestionsInterface models
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return \Tingle\Pmeds\Model\ResourceModel\Questions\Collection
     */
    public function getAllProductQuestions($product);
}
