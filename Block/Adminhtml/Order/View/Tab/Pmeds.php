<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Adminhtml\Order\View\Tab;

use Magento\Framework\Exception\NoSuchEntityException;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Tingle\Pmeds\Block\Form\Fields\Select;

class Pmeds extends \Magento\Backend\Block\Template implements \Magento\Backend\Block\Widget\Tab\TabInterface
{
    /**
     * Template
     *
     * @var string
     */
    protected $_template = 'Tingle_Pmeds::tab/order/pmeds.phtml';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry = null;

    /**
     * @var \Tingle\Pmeds\Api\QuestionnaireFormDataRepositoryInterface
     */
    protected $formDataRepository;

    protected $questionsRepository;

    protected $config;

    private $formData = null;

    private $formattedData = null;

    /**
     * Pmeds constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param ConfigInterface $config
     * @param \Tingle\Pmeds\Api\QuestionnaireFormDataRepositoryInterface $formDataRepository
     * @param \Tingle\Pmeds\Api\QuestionsRepositoryInterface $questionsRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        ConfigInterface $config,
        \Tingle\Pmeds\Api\QuestionnaireFormDataRepositoryInterface $formDataRepository,
        \Tingle\Pmeds\Api\QuestionsRepositoryInterface $questionsRepository,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
        $this->formDataRepository = $formDataRepository;
        $this->questionsRepository = $questionsRepository;
        $this->config = $config;
    }

    /**
     * Retrieve order model instance
     *
     * @return \Magento\Sales\Model\Order
     */
    public function getOrder()
    {
        return $this->coreRegistry->registry('current_order');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabLabel()
    {
        return __('P-meds');
    }

    /**
     * {@inheritdoc}
     */
    public function getTabTitle()
    {
        return __('P-meds');
    }

    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        if ($this->_scopeConfig->isSetFlag(ConfigInterface::XML_PATH_ENABLE)) {
            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Get Tab Class
     *
     * @return string
     */
    public function getTabClass()
    {
        return 'ajax';
    }

    /**
     * Get Class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->getTabClass();
    }

    /**
     * Get Tab Url
     *
     * @return string
     */
    public function getTabUrl()
    {
        return $this->getUrl('tingle/*/tab', ['_current' => true]);
    }

    public function getFormData()
    {
        if ($this->formData === null) {
            try {
                $this->formData = $this->formDataRepository->getByOrderId($this->getOrder()->getId());
            } catch (NoSuchEntityException $e) {
                $this->formData = false;
            }
        }

        return $this->formData;
    }

    public function getAnswersData()
    {
        if ($this->formattedData === null) {
            $formattedData = [];

            foreach ($this->formData->getQuestionnaireFormData() as $sku => $item) {
                $formattedItems = [];

                foreach ($item as $questionMetadata) {
                    $questionModel = $this->questionsRepository->getById($questionMetadata['question_id']);

                    $customerAnswer = $questionMetadata['customer_answer'];
                    $correctAnswer = null;

                    if ($this->config->getType($questionModel->getTypeId()) === Select::TYPE) {
                        $options = json_decode($questionModel->getOptions(), true);
                        foreach ($options as $option) {
                            if ($option['record_id'] == $customerAnswer) {
                                $customerAnswer = $option['row_name'];
                            }
                            if ($option['record_id'] == $questionModel->getAnswer()) {
                                $correctAnswer = $option['row_name'];
                            }
                        }
                    }

                    $formattedItems[] = [
                        'question_id' => $questionModel->getId(),
                        'question_title' => $questionModel->getTitle(),
                        'question_type' => $this->config->getType($questionModel->getTypeId()),
                        'is_required' => $questionModel->getRequired() ? __('Yes') : __('No') ,
                        'customer_answer' => $customerAnswer,
                        'correct_answer' => $correctAnswer
                    ];
                }

                $formattedData[] = [
                    'timestamp' => $item[0]['timestamp'],
                    'product_name' => $this->getProductName($sku),
                    'product_sku' => $sku,
                    'questions' => $formattedItems
                ];
            }

            $this->formattedData = $formattedData;
        }

        return $this->formattedData;
    }

    private function getProductName($sku)
    {
        foreach ($this->getOrder()->getAllItems() as $item) {
            if ($item->getSku() === $sku) {
                return $item->getName();
            }
        }

        return '';
    }
}
