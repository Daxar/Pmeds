<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model\ResourceModel\Questions;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Tingle\Pmeds\Model;
use Tingle\Pmeds\Api\Data\QuestionsInterface as Config;

class Collection extends AbstractCollection
{
    protected $_idFieldName = Config::FIELD_ID;

    protected $_eventPrefix = 'collection_tingle_pmeds';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(
            Model\Questions::class,
            Model\ResourceModel\Questions::class
        );
    }
}
