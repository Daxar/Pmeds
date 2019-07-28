<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Adminhtml\Pmeds;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\Model\View\Result\ForwardFactory;

class NewAction extends Action
{
    private $resultPageFactory;
    private $resultForwardFactory;

    public function __construct(
        Action\Context $context,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
    }

    public function execute()
    {
        if (!$this->getRequest()->getParam('type')) {
            return $this->resultForwardFactory->create()->forward('noroute');
        } else {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
            return $resultRedirect->setPath('*/*/' . $this->getRequest()->getParam('type'));
        }
    }
}
