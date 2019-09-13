<?php
namespace Tingle\Pmeds\Api\Data;

interface ProductQuestionsInterface
{
    const TABLE_NAME = 'tingle_pmeds_product_questions';

    const FIELD_ID = 'id';
    const FIELD_STORE_ID = 'store_id';
    const FIELD_PRODUCT_ID = 'product_id';
    const FIELD_SELECTED_QUESTION_ID = 'selected_question_id';

    /**
     * @return integer
     */
    public function getId();

    /**
     * @return integer
     */
    public function getProductId();

    /**
     * @return integer
     */
    public function getStoreId();

    /**
     * @return integer
     */
    public function getSelectedQuestionId();

    /**
     * @param integer $productId
     * @return $this
     */
    public function setProductId($productId);

    /**
     * @param integer $storeId
     * @return $this
     */
    public function setStoreId($storeId);

    /**
     * @param integer $questionId
     * @return $this
     */
    public function setSelectedQuestionId($questionId);
}
