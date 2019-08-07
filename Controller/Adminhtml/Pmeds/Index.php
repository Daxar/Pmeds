<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Adminhtml\Pmeds;

use Magento\Framework\Controller\ResultFactory;

class Index extends AbstractAction
{
    /**
     * @inheritdoc
     */
    function execute()
    {
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->prepend((__('List of questions')));
        return $resultPage;
    }
}
