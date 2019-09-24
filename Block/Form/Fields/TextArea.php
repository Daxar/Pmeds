<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Form\Fields;

class TextArea extends AbstractField
{
    const TYPE = 'textarea';

    protected $_template = 'Tingle_Pmeds::questions/form/textarea.phtml';
}
