<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model\ResourceModel\ProductQuestions;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Tingle\Pmeds\Model;
use Tingle\Pmeds\Api\Data\ProductQuestionsInterface as Config;

class Collection extends AbstractCollection
{
    protected $_idFieldName = Config::FIELD_ID;

    protected $_eventPrefix = 'collection_product_questions_tingle_pmeds';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(
            Model\ProductQuestions::class,
            Model\ResourceModel\ProductQuestions::class
        );
    }
}
