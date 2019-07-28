<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Adminhtml\Pmeds;

use Magento\Framework\Controller\ResultFactory;

class Text extends AbstractAction
{
    function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}
