<?php declare(strict_types=1);
namespace Tingle\Pmeds\Ui\Options\Product;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Registry;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Tingle\Pmeds\Api\QuestionsRepositoryInterface;

class Source implements OptionSourceInterface
{
    /**
     * @var QuestionsRepositoryInterface
     */
    private $questionsRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var Registry
     */
    private $registry;

    /**
     * Source constructor.
     *
     * @param QuestionsRepositoryInterface $questionsRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param Registry $registry
     */
    public function __construct(
        QuestionsRepositoryInterface $questionsRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        Registry $registry
    ) {
        $this->questionsRepository = $questionsRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->registry = $registry;
    }

    /**
     * Build options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $searchCriteria = $this->searchCriteriaBuilder->create();
        $questions = $this->questionsRepository->getList($searchCriteria)->getItems();

        $options = [];

        /** @var \Tingle\Pmeds\Api\Data\QuestionsInterface $question */
        foreach ($questions as $question) {
            $options[] = [
                'value' => $question->getId(),
                'label' => $question->getTitle()
            ];
        }

        return $options;
    }
}
