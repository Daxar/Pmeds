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

class InstallData implements InstallDataInterface
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
     * Create new attribute set.
     *
     * @param AttributeSetInterfaceFactory $attributeSetInterfaceFactory
     * @param AttributeSetManagementInterface $attributeSetManagement
     * @param EavSetup $eavSetup
     * @param Config $eavConfig
     */
    public function __construct(
        AttributeSetInterfaceFactory $attributeSetInterfaceFactory,
        AttributeSetManagementInterface $attributeSetManagement,
        EavSetup $eavSetup,
        Config $eavConfig
    ) {
        $this->eavConfig = $eavConfig;
        $this->attributeSetInterfaceFactory = $attributeSetInterfaceFactory;
        $this->attributeSetManagement = $attributeSetManagement;
        $this->eavSetup = $eavSetup;
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

        $productEntityId = $this->eavConfig->getEntityType(ProductAttributeInterface::ENTITY_TYPE_CODE)->getId();
        $defaultAttributeSetId = $this->eavSetup->getDefaultAttributeSetId($productEntityId);

        /** @var \Magento\Eav\Api\Data\AttributeSetInterface $attrSet */
        $attrSet = $this->attributeSetInterfaceFactory->create();
        $attrSet->setAttributeSetName(self::ATTRIBUTE_SET_NAME)
            ->setEntityTypeId($productEntityId);
        $this->attributeSetManagement->create(
            ProductAttributeInterface::ENTITY_TYPE_CODE, $attrSet, $defaultAttributeSetId);

        $setup->endSetup();
    }
}
