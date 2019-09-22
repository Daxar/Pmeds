<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Adminhtml\Order\View\Tab;

use Magento\Framework\Exception\NoSuchEntityException;
use Tingle\Pmeds\Api\Data\ConfigInterface;

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

    /** @var \Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterface|null|false  */
    private $formData = null;

    /**
     * Pmeds constructor.
     *
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Tingle\Pmeds\Api\QuestionnaireFormDataRepositoryInterface $formDataRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Tingle\Pmeds\Api\QuestionnaireFormDataRepositoryInterface $formDataRepository,
        array $data = []
    ) {
        $this->coreRegistry = $registry;
        parent::__construct($context, $data);
        $this->formDataRepository = $formDataRepository;
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

    /**
     * @return bool|false|\Tingle\Pmeds\Api\Data\QuestionnaireFormDataInterface|null
     */
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

    /**
     * @return array
     */
    public function getAnswersData()
    {
        return $this->formData->getQuestionnaireFormData();
    }
}
