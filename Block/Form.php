<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block;

use Magento\Framework\View\Element\Template;

class Form extends Template
{
    protected $_template = 'Tingle_Pmeds::questions/form.phtml';

    public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function stuff()
    {
        return 'this is the stuff';
    }

    public function getOptions()
    {

    }

    public function getFormAction()
    {
        return 'WOWOWO';//TODO: Build submit url here
    }
}
