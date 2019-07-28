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

    public function getTitle()
    {
        return $this->getData(self::FIELD_TITLE);
    }

    public function getSortOrder()
    {
        return $this->getData(self::FIELD_SORT_ORDER);
    }

    public function getRequired()
    {
        return $this->getData(self::FIELD_REQUIRED);
    }

    public function getOptions()
    {
        return $this->getData(self::FIELD_OPTIONS);
    }

    public function getAnswer()
    {
        return $this->getData(self::FIELD_ANSWER);
    }

    public function getType()
    {
        return $this->getData(self::FIELD_TYPE);
    }

    public function setTitle($title)
    {
        return $this->setData(self::FIELD_TITLE, $title);
    }
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::FIELD_SORT_ORDER, $sortOrder);
    }

    public function setRequired($required)
    {
        return $this->setData(self::FIELD_REQUIRED, $required);
    }

    public function setOptions($options)
    {
        return $this->setData(self::FIELD_OPTIONS, $options);
    }

    public function setAnswer($answer)
    {
        return $this->setData(self::FIELD_ANSWER, $answer);
    }

    public function setType($type)
    {
        return $this->setData(self::FIELD_TYPE, $type);
    }
}
