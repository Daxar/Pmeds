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

    public function setQuoteId($id)
    {
        return $this->setData(self::FIELD_QUOTE_ID, $id);
    }

    public function setOrderId($id)
    {
        return $this->setData(self::FIELD_ORDER_ID, $id);
    }

    public function setQuestionnaireFormData($data)
    {
        return $this->setData(self::FIELD_QUESTIONNAIRE_FORM_DATA, $data);
    }

    public function getOrderId()
    {
        return $this->getData(self::FIELD_ORDER_ID);
    }

    public function getQuestionnaireFormData()
    {
        return $this->getData(self::FIELD_QUESTIONNAIRE_FORM_DATA);
    }

    public function getQuoteId()
    {
        return $this->getData(self::FIELD_QUOTE_ID);
    }

    public function getId()
    {
        return $this->getData(self::FIELD_ID);
    }
}
