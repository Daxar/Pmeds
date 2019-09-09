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

    const FIELD_SORT_ORDER = 'sort_order';

    const FIELD_TITLE = 'title';

    const FIELD_REQUIRED = 'required';

    const FIELD_OPTIONS = 'options';

    const FIELD_ANSWER = 'answer';

    const FIELD_TYPE_ID = 'type_id';

    /**
     * Data persistor storage key
     */
    const DATA_PERSISTOR_KEY = self::TABLE_NAME;

    const DATA_PERSISTOR_OPTIONS_KEY = self::DATA_PERSISTOR_KEY . '_' . self::FIELD_OPTIONS;

    /**
     * Config XML path //TODO: Future config path extractors here.
     */
    const XML_PATH_SOMETHING = 'something';

    /**
     * Get question id
     *
     * @return integer
     */
    public function getId();

    /**
     * Get question title
     *
     * @return string
     */
    public function getTitle();

    /**
     * Get question sort order
     *
     * @return integer
     */
    public function getSortOrder();

    /**
     * Get is question required
     *
     * @return integer|boolean
     */
    public function getRequired();

    /**
     * Get question options
     *
     * @return array|string
     */
    public function getOptions();

    /**
     * Get correct answer to the question
     *
     * @return string
     */
    public function getAnswer();

    /**
     * Get question type id
     *
     * @return integer
     */
    public function getTypeId();

    /**
     * Set question title
     *
     * @param string $title
     * @return $this
     */
    public function setTitle($title);

    /**
     * Set question sort order
     *
     * @param integer $sortOrder
     * @return $this
     */
    public function setSortOrder($sortOrder);

    /**
     * Set is question required
     *
     * @param integer|boolean $required
     * @return $this
     */
    public function setRequired($required);

    /**
     * Set question options (possible answers list)
     *
     * @param array|string $options
     * @return $this
     */
    public function setOptions($options);

    /**
     * Set question's correct answer
     *
     * @param string $answer
     * @return $this
     */
    public function setAnswer($answer);

    /**
     * Set question type id
     *
     * @param integer $typeId
     * @return $this
     */
    public function setTypeId($typeId);
}
