<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Form;

use Magento\Framework\View\Element\Template;

class Form extends Template
{
    protected $_template = 'Tingle_Pmeds::questions/form.phtml';

    const FORM_ID = 'modal-popup-questions-form';

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

    public function getFormId()
    {
        return self::FORM_ID;
    }

    /**
     * @param \Magento\Catalog\Model\Product $product
     * @return string
     */
    public function getFormFields($product)
    {
        return '<input name="firstname" type="text"><input name="lastname" type="text">';
    }

    public function getFormAction()
    {
        return 'WOWOWO';//TODO: Build submit url here
    }
}
