<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Form\Fields;

class Select extends AbstractField
{
    const TYPE = 'select';

    protected $_template = 'Tingle_Pmeds::questions/form/select.phtml';

    public function getFormattedOptions()
    {
        $options = json_decode($this->getQuestion()->getOptions(), true);
        $i = unserialize($this->getQuestion()->getOptions());

        $formattedOptions = [];

        foreach ($options as $option) {
            $formattedOptions[$option['record_id']] = $option['row_name'];
        }

        return $formattedOptions;
    }

    public function getCorrectAnswer()
    {
        if ($this->getQuestion()->getAnswer() !== null && $this->getIsRequired()) {
            return $this->getQuestion()->getAnswer();
        }

        return null;
    }
}
