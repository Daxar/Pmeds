<?php declare(strict_types=1);
namespace Tingle\Pmeds\Model\Internal;

class OrderFormData
{
    private $answers = [];

    public function __construct(array $answers)
    {
        $this->answers = $answers;
    }
}
