<?php declare(strict_types=1);
namespace TIngle\Pmeds\Block\Form\Fields;

use Magento\Framework\View\Element\Template;

abstract class AbstractField extends Template
{
    const QUESTION = 'question';

    /**
     * @return \Tingle\Pmeds\Api\Data\QuestionsInterface
     */
    protected function getQuestion()
    {
        return $this->getData(self::QUESTION);
    }

    public function getQuestionId()
    {
        return $this->getQuestion()->getId();
    }

    public function getQuestionTitle()
    {
        return $this->getQuestion()->getTitle();
    }

    /**
     * @return boolean
     */
    public function getIsRequired()
    {
        if ($this->getQuestion()->getRequired()) {
            return true;
        }

        return false;
    }

    public function getIsRequiredAttribute()
    {
        if ($this->getIsRequired()) {
            return 'required';
        }

        return '';
    }
}
