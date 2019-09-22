<?php declare(strict_types=1);
namespace Tingle\Pmeds\Ui\Component\Listing\Columns;

use Magento\Framework\Option\ArrayInterface;
use Tingle\Pmeds\Api\Data\ConfigInterface;

class Types implements ArrayInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * Types constructor.
     *
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $result = [];

        foreach ($this->config->getTypes() as $key => $value) {
            $result[] = [
                'value' => $key, 'label' => __(ucfirst($value))
            ];
        }

        return $result;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        $result = [];

        foreach ($this->config->getTypes() as $key => $value) {
            $result[$key] = __(ucfirst($value));
        }

        return $result;
    }
}
