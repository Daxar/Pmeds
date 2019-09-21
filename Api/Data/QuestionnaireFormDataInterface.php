<?php
namespace Tingle\Pmeds\Api\Data;

interface QuestionnaireFormDataInterface
{
    const TABLE_NAME = 'tingle_pmeds_passed_questionnaire_data';

    const FIELD_ID = 'id';

    const FIELD_ORDER_ID = 'order_id';

    const FIELD_QUESTIONNAIRE_FORM_DATA = 'form_data';

    /**
     * @return integer
     */
    public function getId();

    /**
     * @return integer
     */
    public function getOrderId();

    /**
     * @return \Tingle\Pmeds\Model\Internal\OrderFormData
     */
    public function getQuestionnaireFormData();

    /**
     * @param integer $id
     * @return $this
     */
    public function setOrderId($id);

    /**
     * @param string $data
     * @return $this
     */
    public function setQuestionnaireFormData($data);
}
