<?php
namespace Tingle\Pmeds\Api\Data;

interface ConfigInterface
{
    /**
     * All config path options here
     */
    const XML_PATH_BASE = 'pmeds/general/';

    const QUESTIONNAIRE_INTRO_TEXT = 'questions_intro_text';
    const SELECTED_QUESTIONS_LIST = 'selected_questions_list';

    const XML_PATH_ENABLE                      = self::XML_PATH_BASE . 'enable';
    const XML_PATH_INFO_TITLE                  = self::XML_PATH_BASE . 'info_display_title';
    const XML_PATH_INFO_HELP_TEXT              = self::XML_PATH_BASE . 'info_display_help_text';
    const XML_PATH_QUESTIONNAIRE_TITLE         = self::XML_PATH_BASE . 'questionnaire_title';
    const XML_PATH_QUESTIONNAIRE_INTRO         = self::XML_PATH_BASE . 'questionnaire_intro_text';
    const XML_PATH_QUESTIONNAIRE_PASS_TEXT     = self::XML_PATH_BASE . 'questionnaire_pass_text';
    const XML_PATH_QUESTIONNAIRE_FAIL_TEXT     = self::XML_PATH_BASE . 'questionnaire_fail_text';
    const XML_PATH_ENABLE_AT_CATEGORY_PAGE     = self::XML_PATH_BASE . 'add_at_product_listing'; // category page
    const XML_PATH_ENABLE_AT_PRODUCT_PAGE      = self::XML_PATH_BASE . 'add_at_product_page';
    const XML_PATH_ADD_TO_CART_TEXT            = self::XML_PATH_BASE . 'add_to_cart_text';

    /**
     * Get all possible question types
     *
     * @return array
     */
    public function getTypes();

    /**
     * Return id of Pmeds attribute set, false otherwise
     *
     * @return integer|false
     */
    public function getPmedsAttributeSetId();

    /**
     * Get question type class
     *
     * @return array
     */
    public function getQuestionTypes();
}
