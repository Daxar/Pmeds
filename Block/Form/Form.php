<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Form;

use Magento\Framework\View\Element\Template;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Tingle\Pmeds\Api\ProductQuestionsRepositoryInterface;

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

    /**
     * Form constructor.
     *
     * @param Template\Context $context
     * @param ProductQuestionsRepositoryInterface $questionsRepo
     * @param ConfigInterface $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ProductQuestionsRepositoryInterface $questionsRepo,
        ConfigInterface $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->questionsRepo = $questionsRepo;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getFormId()
    {
        return self::FORM_ID;
    }

    /**
     * @return string
     */
    public function getFormFields()
    {
        return $this->buildFields($this->questionsRepo->getAllProductQuestions($this->getData(self::PRODUCT_DATA_KEY)));
    }

    public function getProductQuestionnaireTitle()
    {
        return $this->getData(self::PRODUCT_DATA_KEY)->getData(ConfigInterface::QUESTIONNAIRE_INTRO_TEXT);
    }

    public function getFormAction()
    {
        return 'http://127.0.0.1/tingle/questions/validate';//TODO: Build submit url here
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
