<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Form;

use Magento\Framework\View\Element\Template;
use Magento\Backend\Block\Widget\Grid\Column\Filter\Store;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Tingle\Pmeds\Api\ProductQuestionsRepositoryInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Form extends Template
{
    const FORM_ID = 'modal-popup-questions-form';

    const PRODUCT_DATA_KEY = 'product';

    protected $_template = 'Tingle_Pmeds::questions/form.phtml';

    /**
     * @var ProductQuestionsRepositoryInterface
     */
    protected $questionsRepo;

    /**
     * @var ConfigInterface
     */
    protected $config;

    protected $date;

    /**
     * Form constructor.
     *
     * @param Template\Context $context
     * @param ProductQuestionsRepositoryInterface $questionsRepo
     * @param ConfigInterface $config
     * @param DateTime $date
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ProductQuestionsRepositoryInterface $questionsRepo,
        ConfigInterface $config,
        DateTime $date,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->questionsRepo = $questionsRepo;
        $this->config = $config;
        $this->date = $date;
    }

    /**
     * @return string
     */
    public function getFormId()
    {
        return self::FORM_ID;
    }

    public function getTimeStamp()
    {
        return $this->date->date();
    }

    /**
     * @return string
     */
    public function getFormFields()
    {
        $questions = $this->questionsRepo->getAllProductQuestions($this->getData(self::PRODUCT_DATA_KEY));

        if (!$questions->getSize()) {
            $questions = $this->questionsRepo->getAllProductQuestions($this->getData(self::PRODUCT_DATA_KEY)->setStoreId(Store::ALL_STORE_VIEWS));
        }

        return $this->buildFields($questions);
    }

    public function getProductQuestionnaireTitle()
    {
        return $this->getData(self::PRODUCT_DATA_KEY)->getData(ConfigInterface::QUESTIONNAIRE_INTRO_TEXT);
    }

    /**
     * @param \Tingle\Pmeds\Model\ResourceModel\Questions\Collection $questions
     * @return string
     * @throws \Exception
     */
    private function buildFields($questions)
    {
        $html = '';

        foreach ($questions as $question) {
            $html .= $this->buildField($question);
        }

        return $html;
    }

    /**
     * @param \Tingle\Pmeds\Api\Data\QuestionsInterface $question
     * @return string
     * @throws \Exception
     */
    private function buildField($question)
    {
        $questionTypes = $this->config->getQuestionTypes();

        return $this->renderFieldHtml(
            $question,
            $questionTypes[$question->getTypeId()]
        );
    }

    /**
     * @param \Tingle\Pmeds\Api\Data\QuestionsInterface $question
     * @param string $className
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function renderFieldHtml($question, $className)
    {
        $html = $this->getLayout()->createBlock(
            $className,
            'tingle.questions.form.' . $question->getId(),
            [
                'data' => [
                    Fields\AbstractField::QUESTION => $question
                ]
            ]
        )->toHtml();

        return $html;
    }
}
