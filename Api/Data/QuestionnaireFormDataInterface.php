<?php
namespace Tingle\Pmeds\Api\Data;

interface QuestionnaireFormDataInterface
{
    const TABLE_NAME = 'tingle_pmeds_passed_questionnaire_data';

    const FIELD_ID = 'id';

    const FIELD_ORDER_ID = 'order_id';

    const FIELD_QUESTIONNAIRE_FORM_DATA = 'form_data';

    const FIELD_CREATED_AT = 'created_at';

    const FIELD_CUSTOMER_IP_ADDRESS = 'customer_ip_address';

    /**
     * @return integer
     */
    public function getId();

    /**
     * @return integer
     */
    public function getOrderId();

    /**
     * @return array
     */
    public function getQuestionnaireFormData();

    /**
     * @return string
     */
    public function getCustomerIpAddress();

    /**
     * @return string|integer
     */
    public function getCreatedAt();

    /**
     * @param integer $id
     * @return $this
     */
    public function setOrderId($id);

    /**
     * @param array $data
     * @return $this
     */
    public function setQuestionnaireFormData($data);

    /**
     * @param string $ipAddress
     * @return $this
     */
    public function setCustomerIdAddress($ipAddress);
}
