<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Adminhtml\Question\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\AbstractBlock;

class AbstractButton extends AbstractBlock
{
    protected $context;

    public function __construct(
        Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->context = $context;
    }

    public function getUrl($route = '', $params = [])
    {
        return $this->_urlBuilder->getUrl($route, $params);
    }
}
