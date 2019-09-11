<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Tingle\Pmeds\Setup\InstallData;

class Config implements ConfigInterface
{
    /**
     * @var AttributeSetRepositoryInterface
     */
    private $attributeSetRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    private $typesList = [
        0 => 'text',
        1 => 'textarea',
        2 => 'select'
    ];

    public function __construct(
        AttributeSetRepositoryInterface $attributeSetRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->attributeSetRepository = $attributeSetRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * @inheritdoc
     */
    public function getTypes()
    {
        return $this->typesList;
    }

    /**
     * @inheritdoc
     */
    public function getPmedsAttributeSetId()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $attributeSets = $this->attributeSetRepository->getList($searchCriteria)->getItems();

        /** @var \Magento\Eav\Api\Data\AttributeSetInterface $attributeSet */
        foreach ($attributeSets as $attributeSet) {
            if ($attributeSet->getAttributeSetName() === InstallData::ATTRIBUTE_SET_NAME) {
                return $attributeSet->getAttributeSetId();
            }
        }

        return false;
    }
}
