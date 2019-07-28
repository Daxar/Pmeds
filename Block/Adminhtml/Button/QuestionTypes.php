<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Adminhtml\Button;

use Magento\Backend\Block\Widget;

class QuestionTypes extends Widget\Container
{
    private $types = [
        'text' => 'Text',
        'select' => 'Options',
        'boolean' => 'Yes/no'
    ];

    protected function _prepareLayout()
    {
        $addButtonProps = [
            'id' => 'custom_action_list',
            'label' => __('New Question'),
            'class' => 'add',
            'button_class' => '',
            'class_name' => 'Magento\Backend\Block\Widget\Button\SplitButton',
            'options' => $this->_getCustomActionListOptions(),
        ];
        $this->buttonList->add('add_new', $addButtonProps);

        return parent::_prepareLayout();
    }

    /**
     * Retrieve options for 'CustomActionList' split button
     *
     * @return array
     */
    protected function _getCustomActionListOptions()
    {
        $splitButtonOptions = [];

        foreach ($this->types as $key => $type) {
            $splitButtonOptions[$key] = [
                'label'   => __($type),
                'onclick' => 'setLocation("' . $this->getCreateUrl($key) . '")',
                'default' => $this->types['text'] == $type,
            ];
        }

        return $splitButtonOptions;
    }

    protected function getCreateUrl($type)
    {
        return $this->getUrl(
            'tingle/pmeds/new',
            ['type' => $type]
        );
    }
}
