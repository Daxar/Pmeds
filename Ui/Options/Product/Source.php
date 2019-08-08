<?php declare(strict_types=1);
namespace Tingle\Pmeds\Ui\Options\Product;

use Magento\Framework\Data\OptionSourceInterface;

class Source implements OptionSourceInterface
{
    /**
     * Build options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $newOptions = [
            ['value' => '1', 'label' => '-- none ---------------------------------------'],
            ['value' => '2', 'label' => '-- none2 --'],
        ];

        return $newOptions;
    }
}
