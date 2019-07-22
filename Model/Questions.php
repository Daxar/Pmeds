<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Magento\Framework\Model\AbstractModel;
use Tingle\Pmeds\Model\ResourceModel\Questions as Resource;
use Tingle\Pmeds\Api\Data\QuestionsInterface;

class Questions extends AbstractModel implements QuestionsInterface
{
    protected $_eventPrefix = 'tingle_question';

    protected $_cacheTag = 'tingle_questions';

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
}
