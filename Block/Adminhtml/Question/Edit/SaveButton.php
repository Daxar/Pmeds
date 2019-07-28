<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Adminhtml\Question\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class SaveButton extends AbstractButton implements ButtonProviderInterface
{
    /**
     * Save button
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 20,
        ];
    }
}
