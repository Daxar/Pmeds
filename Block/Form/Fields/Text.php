<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Form\Fields;

class Text extends AbstractField
{
    const TYPE = 'text';

    protected $_template = 'Tingle_Pmeds::questions/form/text.phtml';
}
