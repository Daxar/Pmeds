<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Magento\Framework\Model\AbstractModel;
use Tingle\Pmeds\Model\ResourceModel\Questions as Resource;
use Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterface;

class QuestionnaireFormData extends AbstractModel implements QuestionnaireFormDataInterface
{
    protected $_eventPrefix = 'tingle_questionnaire_data';

    protected $_cacheTag = 'tingle_questionnaire_data';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(Resource::class);
    }

    /**
     * @inheritDoc
     */
    public function setOrderId($id)
    {
        return $this->setData(self::FIELD_ORDER_ID, $id);
    }

    /**
     * @inheritDoc
     */
    public function setQuestionnaireFormData($data)
    {
        return $this->setData(self::FIELD_QUESTIONNAIRE_FORM_DATA, $data);
    }

    /**
     * @inheritDoc
     */
    public function setCustomerIdAddress($ipAddress)
    {
        return $this->setData(self::FIELD_CUSTOMER_IP_ADDRESS, $ipAddress);
    }

    /**
     * @inheritDoc
     */
    public function getOrderId()
    {
        return $this->getData(self::FIELD_ORDER_ID);
    }

    /**
     * @inheritDoc
     */
    public function getQuestionnaireFormData()
    {
        return $this->getData(self::FIELD_QUESTIONNAIRE_FORM_DATA);
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->getData(self::FIELD_ID);
    }

    /**
     * @inheritDoc
     */
    public function getCreatedAt()
    {
        return $this->getData(self::FIELD_CREATED_AT);
    }

    /**
     * @inheritDoc
     */
    public function getCustomerIpAddress()
    {
        return $this->getData(self::FIELD_CUSTOMER_IP_ADDRESS);
    }

    public function beforeSave()
    {
        if ($this->getQuestionnaireFormData() !== null) {
            $this->setQuestionnaireFormData(serialize($this->getQuestionnaireFormData()));
        }

        return parent::beforeSave();
    }

    protected function _afterLoad()
    {
        if ($this->getQuestionnaireFormData() !== null) {
            $this->setQuestionnaireFormData(unserialize($this->getQuestionnaireFormData()));
        }

        return parent::_afterLoad();
    }
}
