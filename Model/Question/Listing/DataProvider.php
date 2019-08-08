<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model\Question\Listing;

use Tingle\Pmeds\Model\ResourceModel\Questions\CollectionFactory;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{
    /**
     * DataProvider constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $questionsCollectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $questionsCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $questionsCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
}
