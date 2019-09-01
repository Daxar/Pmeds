<?php declare(strict_types=1);
namespace Tingle\Pmeds\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Ui\Component\Form\Element\MultiSelect;
use Magento\Ui\Component\Form\Element\Textarea;

class Pmeds extends AbstractModifier
{
    public function modifyData(array $data)
    {
        return $data;
    }

    public function modifyMeta(array $meta)
    {


        return $meta;
    }

    protected function getIsRequireFieldConfig($sortOrder)
    {
        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Required'),
                        'componentType' => Field::NAME,
                        'formElement' => Checkbox::NAME,
                        'dataScope' => static::FIELD_IS_REQUIRE_NAME,
                        'dataType' => Text::NAME,
                        'sortOrder' => $sortOrder,
                        'value' => '1',
                        'valueMap' => [
                            'true' => '1',
                            'false' => '0'
                        ],
                    ],
                ],
            ],
        ];
    }
}
