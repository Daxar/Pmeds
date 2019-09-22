<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Adminhtml\Pmeds;

use Magento\Backend\App\Action;

abstract class AbstractAction extends Action
{
    /** Default action path */
    const BASE_ACTION_PATH = 'tingle/pmeds';

    /** Adminhtml resource //TODO: Check if resource_name haven't changed once development is done */
    const ADMIN_RESOURCE = 'Tingle_Pmeds::config';

    /**
     * @inheritdoc
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }

    /**
     * @inheritdoc
     */
    abstract function execute();
}
