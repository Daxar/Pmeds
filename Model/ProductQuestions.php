<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Magento\Framework\Model\AbstractModel;
use Tingle\Pmeds\Api\Data\ProductQuestionsInterface;
use Tingle\Pmeds\Model\ResourceModel\ProductQuestions as Resource;

class ProductQuestions extends AbstractModel implements ProductQuestionsInterface
{
    protected $_eventPrefix = 'tingle_product_question';

    protected $_cacheTag = 'tingle_product_questions';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Resource::class);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getData(self::FIELD_ID);
    }

    /**
     * @inheritdoc
     */
    public function getProductId()
    {
        return $this->getData(self::FIELD_PRODUCT_ID);
    }

    /**
     * @inheritdoc
     */
    public function getStoreId()
    {
        return $this->getData(self::FIELD_STORE_ID);
    }

    /**
     * @inheritdoc
     */
    public function getSelectedQuestionId()
    {
        return $this->getData(self::FIELD_SELECTED_QUESTION_ID);
    }

    /**
     * @inheritdoc
     */
    public function setSelectedQuestionId($questionId)
    {
        return $this->setData(self::FIELD_SELECTED_QUESTION_ID, $questionId);
    }

    /**
     * @inheritdoc
     */
    public function setProductId($productId)
    {
        return $this->setData(self::FIELD_PRODUCT_ID, $productId);
    }

    /**
     * @inheritdoc
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::FIELD_STORE_ID, $storeId);
    }
}
