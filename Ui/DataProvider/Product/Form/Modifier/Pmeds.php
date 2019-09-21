<?php declare(strict_types=1);
namespace Tingle\Pmeds\Ui\DataProvider\Product\Form\Modifier;

use Magento\Framework\Registry;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\FilterBuilder;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\MultiSelect;
use Magento\Ui\Component\Form\Element\Textarea;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Tingle\Pmeds\Ui\Options\Product\Source;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Tingle\Pmeds\Api\QuestionsRepositoryInterface;
use Tingle\Pmeds\Api\ProductQuestionsRepositoryInterface;

class Pmeds extends AbstractModifier
{
    /**
     * @var Source
     */
    private $options;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var \Magento\Catalog\Model\Product
     */
    private $product;

    /**
     * @var QuestionsRepositoryInterface
     */
    private $questionsRepository;

    /**
     * @var ProductQuestionsRepositoryInterface
     */
    private $productQuestionsRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var FilterBuilder
     */
    private $filterBuilder;

    /**
     * Pmeds constructor.
     * @param Source $options
     * @param ConfigInterface $config
     * @param Registry $registry
     * @param QuestionsRepositoryInterface $questionsRepository
     * @param ProductQuestionsRepositoryInterface $productQuestionsRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param FilterBuilder $filterBuilder
     */
    public function __construct(
        Source $options,
        ConfigInterface $config,
        Registry $registry,
        QuestionsRepositoryInterface $questionsRepository,
        ProductQuestionsRepositoryInterface $productQuestionsRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        FilterBuilder $filterBuilder
    ) {
        $this->options = $options;
        $this->config = $config;
        $this->registry = $registry;
        $this->questionsRepository = $questionsRepository;
        $this->productQuestionsRepository = $productQuestionsRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->filterBuilder = $filterBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $this->product = $this->registry->registry('product');

        return $this->addPmedsTab($meta);
    }

    /**
     * Build 'Pmeds Questions' tab
     *
     * @param array $meta
     * @return array
     */
    protected function addPmedsTab($meta)
    {
        $meta = array_replace_recursive(
            $meta,
            [
                'tingle_pmeds_questions_setup' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'label' => __('P-Meds questions'),
                                'collapsible' => true,
                                'componentType' => Fieldset::NAME,
                                'dataScope' => self::DATA_SCOPE_PRODUCT,
                                'disabled' => false,
                                'sortOrder' => 64
                            ],
                        ],
                    ],
                    'children' => [
                        'questions_group' => $this->getTabContent()
                    ],
                ],
            ]
        );

        return $meta;
    }

    /**
     * Pmeds container declaration
     *
     * @return array
     */
    protected function getTabContent()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => 'container',
                        'componentType' => 'container',
                        'label' => false,
                    ],
                ],
            ],
            'children' => [
                ConfigInterface::QUESTIONNAIRE_INTRO_TEXT => $this->getQuestionnaireIntroField(),
                ConfigInterface::SELECTED_QUESTIONS_LIST => $this->buildSelectedOptions()
            ]
        ];
    }

    /**
     * Questionnaire intro field declaration
     *
     * @return array
     */
    protected function getQuestionnaireIntroField()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => Textarea::NAME,
                        'componentType' => Field::NAME,
                        'visible' => true,
                        'scopeLabel' => '[STORE VIEW]',
                        'dataScope' => ConfigInterface::QUESTIONNAIRE_INTRO_TEXT,
                        'required' => false,
                        'label' => __('Questionnaire intro'),
                        'value' => $this->product->getData(ConfigInterface::QUESTIONNAIRE_INTRO_TEXT)
                    ]
                ]
            ]
        ];
    }

    /**
     * Build multi-select uiComponent for product questions
     *
     * @return array
     */
    protected function buildSelectedOptions()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Select questions'),
                        'componentType' => Field::NAME,
                        'formElement' => MultiSelect::NAME,
                        'dataScope' => ConfigInterface::SELECTED_QUESTIONS_LIST,
                        'dataType' => Text::NAME,
                        'sortOrder' => 20,
                        'scopeLabel' => '[STORE VIEW]',
                        'required' => false,
                        'options' => $this->options->toOptionArray(),
                        'visible' => true,
                        'disabled' => false,
                        'value' => $this->getSelectedQuestionsList()
                    ],
                ],
            ],
        ];
    }

    private function getSelectedQuestionsList()
    {
        $list = '';

        $productQuestions = $this->productQuestionsRepository->getAllProductQuestionsMetaData($this->product);

        /** @var \Tingle\Pmeds\Api\Data\ProductQuestionsInterface $productQuestion */
        foreach ($productQuestions->getItems() as $productQuestion) {
            if (!empty($list)) {
                $list .= ',';
            }
            $list .= $productQuestion->getSelectedQuestionId();
        }

        return $list;
    }
}
