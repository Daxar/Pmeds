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
        if ($this->dataPersistor->get(Config::DATA_PERSISTOR_KEY)) {
            var_dump($this->dataPersistor->get(Config::DATA_PERSISTOR_KEY));
        }

        return [
            ['value' => 'inline', 'label' => __('Inline')],
            ['value' => 'bottomright', 'label' => __('Bottom Right')],
            ['value' => 'bottomleft', 'label' => __('Bottom Left')],
        ];
    }
}
