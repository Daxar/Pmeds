<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Adminhtml\Question\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class DeleteButton extends AbstractButton implements ButtonProviderInterface
{
    /**
     * Delete button
     *
     * @return array
     */
    public function getButtonData()
    {
        return [
            'id' => 'delete',
            'label' => __('Delete'),
            'on_click' => "deleteConfirm('" .__('Are you sure you want to delete this category?') ."', '"
                . $this->getDeleteUrl() . "', {data: {}})",
            'class' => 'delete',
            'sort_order' => 30
        ];
    }

    /**
     * @return string
     */
    protected function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['id' => $this->context->getRequest()->getParam('page_id')]);
    }
}
