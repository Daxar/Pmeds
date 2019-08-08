<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Adminhtml\Question\Edit;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\AbstractBlock;

class AbstractButton extends AbstractBlock
{
    /**
     * Get|build url for provided path
     *
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = [])
    {
        return $this->_urlBuilder->getUrl($route, $params);
    }
}
