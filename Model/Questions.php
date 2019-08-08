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

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        return $this->getData(self::FIELD_TITLE);
    }

    /**
     * @inheritdoc
     */
    public function getSortOrder()
    {
        return $this->getData(self::FIELD_SORT_ORDER);
    }

    /**
     * @inheritdoc
     */
    public function getRequired()
    {
        return $this->getData(self::FIELD_REQUIRED);
    }

    /**
     * @inheritdoc
     */
    public function getOptions()
    {
        return $this->getData(self::FIELD_OPTIONS);
    }

    /**
     * @inheritdoc
     */
    public function getAnswer()
    {
        return $this->getData(self::FIELD_ANSWER);
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return $this->getData(self::FIELD_TYPE);
    }

    /**
     * @inheritdoc
     */
    public function setTitle($title)
    {
        return $this->setData(self::FIELD_TITLE, $title);
    }

    /**
     * @inheritdoc
     */
    public function setSortOrder($sortOrder)
    {
        return $this->setData(self::FIELD_SORT_ORDER, $sortOrder);
    }

    /**
     * @inheritdoc
     */
    public function setRequired($required)
    {
        return $this->setData(self::FIELD_REQUIRED, $required);
    }

    /**
     * @inheritdoc
     */
    public function setOptions($options)
    {
        return $this->setData(self::FIELD_OPTIONS, $options);
    }

    /**
     * @inheritdoc
     */
    public function setAnswer($answer)
    {
        return $this->setData(self::FIELD_ANSWER, $answer);
    }

    /**
     * @inheritdoc
     */
    public function setType($type)
    {
        return $this->setData(self::FIELD_TYPE, $type);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave()
    {
        $this->setOptions(json_encode($this->getOptions()));
        return parent::beforeSave();
    }

    /**
     * @inheritdoc
     */
    public function afterLoad()
    {
        $this->setOptions(json_decode($this->getOptions()));
        return parent::afterLoad();
    }
}
