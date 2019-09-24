<?php declare(strict_types=1);
namespace Tingle\Pmeds\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Api\AttributeSetManagementInterface;
use Magento\Eav\Api\Data\AttributeSetInterfaceFactory;
use Magento\Eav\Model\Config;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Tingle\Pmeds\Api\Data\ConfigInterface;

class UpgradeData implements UpgradeDataInterface
{
    const ATTRIBUTE_SET_NAME = 'P-Meds';

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

    /**
     * @var EavSetupFactory
     */
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
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        if (version_compare($context->getVersion(), '0.8.4', '<')) {
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

            $this->addQuestionnaireIntroAttribute($setup);
        }

        $setup->endSetup();
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @return void
     * @throws \Exception
     */
    private function addQuestionnaireIntroAttribute($setup)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            Product::ENTITY,
            ConfigInterface::QUESTIONNAIRE_INTRO_TEXT,
            [
                'type' => 'varchar',
                'label' => 'Questionnaire intro text',
                'input' => 'string',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'visible' => false,
                'user_defined' => false,
                'unique' => false,
                'group' => 'Content'
            ]
        );
    }
}
