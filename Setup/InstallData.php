<?php declare(strict_types=1);
namespace Tingle\Pmeds\Setup;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Api\AttributeSetManagementInterface;
use Magento\Eav\Api\Data\AttributeSetInterfaceFactory;
use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Model\Product;
use Tingle\Pmeds\Api\Data\ConfigInterface;

class InstallData implements InstallDataInterface
{
    const ATTRIBUTE_SET_NAME = 'P-Meds';

    const QUESTIONAIRE_INTRO_TEXT = 'questions_intro_text';

    const SELECTED_QUESTIONS_LIST = 'selected_questions_list';

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * @var AttributeSetInterfaceFactory
     */
    private $attributeSetInterfaceFactory;

    /**
     * @var AttributeSetManagementInterface
     */
    private $attributeSetManagement;

    /**
     * @var EavSetup
     */
    private $eavSetup;

    /**
     * @var ConfigInterface
     */
    private $config;

    private $eavSetupFactory;

    /**
     * Create new attribute set.
     *
     * @param AttributeSetInterfaceFactory $attributeSetInterfaceFactory
     * @param AttributeSetManagementInterface $attributeSetManagement
     * @param EavSetup $eavSetup
     * @param Config $eavConfig
     * @param ConfigInterface $config
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        AttributeSetInterfaceFactory $attributeSetInterfaceFactory,
        AttributeSetManagementInterface $attributeSetManagement,
        EavSetup $eavSetup,
        Config $eavConfig,
        ConfigInterface $config,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->eavConfig = $eavConfig;
        $this->attributeSetInterfaceFactory = $attributeSetInterfaceFactory;
        $this->attributeSetManagement = $attributeSetManagement;
        $this->eavSetup = $eavSetup;
        $this->config = $config;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    /**
     * Create 'P-Meds' attribute set
     *
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (!$this->config->getPmedsAttributeSetId()) {
            $productEntityId = $this->eavConfig->getEntityType(ProductAttributeInterface::ENTITY_TYPE_CODE)->getId();
            $defaultAttributeSetId = $this->eavSetup->getDefaultAttributeSetId($productEntityId);

            /** @var \Magento\Eav\Api\Data\AttributeSetInterface $attrSet */
            $attrSet = $this->attributeSetInterfaceFactory->create();
            $attrSet->setAttributeSetName(self::ATTRIBUTE_SET_NAME)
                ->setEntityTypeId($productEntityId);
            $this->attributeSetManagement->create(
                ProductAttributeInterface::ENTITY_TYPE_CODE, $attrSet, $defaultAttributeSetId
            );
        }

        $this->addProductAttributes($setup);

        $setup->endSetup();
    }

    /**
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup
     */
    private function addProductAttributes($setup)
    {
        $this->addQuestionnaireIntroAttribute($setup);
//        $this->addSelectedQuestionsAttribute($setup);
    }

    private function addQuestionnaireIntroAttribute($setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            self::QUESTIONAIRE_INTRO_TEXT,
            [
                'type' => 'varchar',
                'label' => 'Questionnaire intro text',
                'input' => 'string',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'visible' => false,
                'user_defined' => false,
                'unique' => false,
                'group' => 'General'
            ]
        );
    }

    private function addSelectedQuestionsAttribute($setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            Product::ENTITY,
            self::SELECTED_QUESTIONS_LIST,
            [
                'type' => 'varchar',
                'label' => 'Selected questions list',
                'input' => 'string',
//                'backend' => Backend::class,
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_WEBSITE,
                'visible' => true,
                'user_defined' => false,
                'unique' => false,
                'group' => 'General'
            ]
        );
    }
}
