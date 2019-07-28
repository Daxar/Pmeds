<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Adminhtml\Pmeds;

class Select extends AbstractAction
{
    function execute()
    {
        /** TODO: Implement it */
        $redirect = $this->resultRedirectFactory->create();
        $this->messageManager->addSuccessMessage('Not yet implemented.');
        return $redirect->setPath('tingle/pmeds/index');
    }
}
