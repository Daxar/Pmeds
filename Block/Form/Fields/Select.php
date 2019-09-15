<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Form\Fields;

class Select extends AbstractField
{
    const TYPE = 'select';

    protected $_template = 'Tingle_Pmeds::questions/form/select.phtml';

    public function getFormattedOptions()
    {
        $options = json_decode($this->getQuestion()->getOptions(), true);

        $formattedOptions = [];

        foreach ($options as $option) {
            $formattedOptions[$option['record_id']] = $option['row_name'];
        }

        return $formattedOptions;
    }

    public function getCorrectAnswer()
    {
        $answer = $this->getQuestion()->getAnswer();

        if ($answer !== null && $this->getIsRequired()) {
            return $answer;
        }

        return false;
    }
}
