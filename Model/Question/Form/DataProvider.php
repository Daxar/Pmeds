<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model\Question\Form;

use Tingle\Pmeds\Model\ResourceModel\Questions\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Json\DecoderInterface;
use Tingle\Pmeds\Api\Data\QuestionsInterface as Config;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var \Tingle\Pmeds\Model\ResourceModel\Questions\Collection
     */
    protected $collection;

    /**
     * @var DecoderInterface
     */
    protected $decoder;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $questionsCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param DecoderInterface $decoder
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $questionsCollectionFactory,
        DataPersistorInterface $dataPersistor,
        DecoderInterface $decoder,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $questionsCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->decoder = $decoder;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \Tingle\Pmeds\Model\Questions $model */
        foreach ($items as $model) {
            if (is_string($model->getOptions())) {
                $model->setOptions($this->decoder->decode($model->getOptions()));
            }
            $this->loadedData[$model->getId()] = $model->getData();
        }
        $data = $this->dataPersistor->get(Config::DATA_PERSISTOR_KEY);

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear(Config::DATA_PERSISTOR_KEY);
        }

        return $this->loadedData;
    }
}
