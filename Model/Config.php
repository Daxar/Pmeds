<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model;

use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Tingle\Pmeds\Block\Form\Fields;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Tingle\Pmeds\Setup\UpgradeData;

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
        0 => Fields\Text::TYPE,
        1 => Fields\TextArea::TYPE,
        2 => Fields\Select::TYPE
    ];

    private $typesClasses = [
        0 => Fields\Text::class,
        1 => Fields\TextArea::class,
        2 => Fields\Select::class
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

        /** @var \Magento\Eav\Api\Data\AttributeSetInterface $attributeSet */ // TODO: Optimize this part
        foreach ($attributeSets as $attributeSet) {
            if ($attributeSet->getAttributeSetName() === UpgradeData::ATTRIBUTE_SET_NAME) {
                return $attributeSet->getAttributeSetId();
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function getQuestionTypes()
    {
        return $this->typesClasses;
    }

    /**
     * @inheritDoc
     */
    public function getType($integer)
    {
        $types = $this->getTypes();

        return $types[$integer];
    }
}
