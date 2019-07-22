<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Adminhtml\Pmeds;

use Magento\Backend\App\Action;

abstract class AbstractAction extends Action
{
    const BASE_ACTION_PATH = 'tingle/pmeds';

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
