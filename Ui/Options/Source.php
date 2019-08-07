<?php declare(strict_types=1);
namespace Tingle\Pmeds\Ui\Options;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Tingle\Pmeds\Api\Data\QuestionsInterface as Config;

class Source implements OptionSourceInterface
{
    private $dataPersistor;

    public function __construct(DataPersistorInterface $dataPersistor)
    {
        $this->dataPersistor = $dataPersistor;
    }

    public function toOptionArray()
    {
        $newOptions = [['value' => '', 'label' => '-- none --']];

        if ($options = $this->dataPersistor->get(Config::DATA_PERSISTOR_OPTIONS_KEY)) {
            foreach($options as $option) {
                $newOptions[] = [
                    'value' => $option->record_id,
                    'label' => $option->row_name
                ];
            }

            $this->dataPersistor->clear(Config::DATA_PERSISTOR_OPTIONS_KEY);
        }

        return $newOptions;
    }
}
