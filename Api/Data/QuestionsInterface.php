<?php
namespace Tingle\Pmeds\Api\Data;

interface QuestionsInterface
{
    /** Table name */
    const TABLE_NAME = 'tingle_pmeds_questions';

    /**
     * Database fields
     */
    const FIELD_ID = 'id';

    const FIELD_TYPE = 'type';

    const FIELD_SORT_ORDER = 'sort_order';

    const FIELD_TITLE = 'title';

    const FIELD_REQUIRED = 'required';

    const FIELD_OPTIONS = 'options';

    const FIELD_ANSWER = 'answer';

    /**
     * Data persistor storage key
     */
    const DATA_PERSISTOR_KEY = self::TABLE_NAME;

    const DATA_PERSISTOR_OPTIONS_KEY = self::DATA_PERSISTOR_KEY . '_' . self::FIELD_OPTIONS;

    /**
     * Config XML path
     */
    const XML_PATH_SOMETHING = 'something';

    public function getId();

    public function getTitle();

    public function getSortOrder();

    public function getRequired();

    public function getOptions();

    public function getAnswer();

    public function getType();

    public function setTitle($title);

    public function setSortOrder($sortOrder);

    public function setRequired($required);

    public function setOptions($options);

    public function setAnswer($answer);

    public function setType($type);
}
