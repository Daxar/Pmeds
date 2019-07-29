<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Adminhtml\Pmeds;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Tingle\Pmeds\Api\Data\QuestionsInterface as Config;

class Edit extends AbstractAction
{
    private $dataPersistor;

    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
    }

    function execute()
    {

        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}
