<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Adminhtml\Product;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Set;
use Tingle\Pmeds\Setup\InstallData as Config;
use Magento\Backend\Block\Template;

class Tab extends Template
{
    /**
     * @var CollectionFactory
     */
    protected $attributeSetCollection;

    /**
     * Tab constructor.
     *
     * @param Template\Context $context
     * @param CollectionFactory $attributeSetCollection
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        CollectionFactory $attributeSetCollection,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->attributeSetCollection = $attributeSetCollection;
    }

    /**
     * @return integer
     */
    public function getPmedsAttributeSetId()
    {
        $attributeSetCollection = $this->attributeSetCollection->create()
            ->addFieldToSelect(Set::KEY_ATTRIBUTE_SET_ID)
            ->addFieldToFilter(Set::KEY_ATTRIBUTE_SET_NAME, Config::ATTRIBUTE_SET_NAME)
            ->getFirstItem()
            ->toArray();

        $attributeSetId = (int) $attributeSetCollection[Set::KEY_ATTRIBUTE_SET_ID];

        return $attributeSetId;
  }
}
