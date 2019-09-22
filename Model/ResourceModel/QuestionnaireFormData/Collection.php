<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model\ResourceModel\QuestionnaireFormData;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Tingle\Pmeds\Model;
use Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterface as Config;

class Collection extends AbstractCollection
{
    protected $_idFieldName = Config::FIELD_ID;

    protected $_eventPrefix = 'collection_questionnaire_form_data_tingle_pmeds';

    /**
     * @inheritdoc
     */
    protected function _construct()
    {
        $this->_init(
            Model\QuestionnaireFormData::class,
            Model\ResourceModel\QuestionnaireFormData::class
        );
    }
}
