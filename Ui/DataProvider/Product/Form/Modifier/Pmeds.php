<?php declare(strict_types=1);
namespace Tingle\Pmeds\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Element\MultiSelect;
use Magento\Ui\Component\Form\Element\Textarea;
use Magento\Ui\Component\Form\Element\Hidden;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Ui\Component\Form\Field;
use Magento\Framework\Registry;
use Tingle\Pmeds\Ui\Options\Product\Source;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Tingle\Pmeds\Setup\InstallData as Config;

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
     * Pmeds constructor.
     *
     * @param Source $options
     * @param ConfigInterface $config
     * @param Registry $registry
     */
    public function __construct(
        Source $options,
        ConfigInterface $config,
        Registry $registry
    ) {
        $this->options = $options;
        $this->config = $config;
        $this->registry = $registry;
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
                Config::QUESTIONAIRE_INTRO_TEXT       => $this->getQuestionnaireIntroField(),
                Config::SELECTED_QUESTIONS_LIST       => $this->getQuestionsField(),
                'questions_tab_visibility_controller' => $this->getVisibilityController()
            ]
        ];
    }

    /**
     * Selected questions field (multi-select) declaration
     *
     * @return array
     */
    protected function getQuestionsField()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement' => MultiSelect::NAME,
                        'componentType' => Field::NAME,
                        'options' => $this->options->toOptionArray(),
                        'visible' => true,
                        'required' => false,
                        'label' => __('Questions')
                    ]
                ]
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
                        'required' => false,
                        'label' => __('Questionnaire intro'),
                        'value' => $this->product->getData(Config::QUESTIONAIRE_INTRO_TEXT)
                    ]
                ]
            ]
        ];
    }

    /**
     * This uiComponent is responsible for Pmeds Tab visibility (based on currently selected attribute set)
     *
     * @return array
     */
    protected function getVisibilityController()
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'formElement'   => Hidden::NAME,
                        'componentType' => Field::NAME,
                        'component'     => 'Tingle_Pmeds/js/component/disable-on-attribute/tab',
                        'value'         => $this->config->getPmedsAttributeSetId()
                    ],
                ]
            ]
        ];
    }
}
