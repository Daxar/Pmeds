<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model\ResourceModel;

use Tingle\Pmeds\Api\Data\ProductQuestionsInterface as Config;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ProductQuestions extends AbstractDb
{
    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Config::TABLE_NAME, Config::FIELD_ID);
    }
}
