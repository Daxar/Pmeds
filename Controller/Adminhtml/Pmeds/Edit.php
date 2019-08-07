<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Adminhtml\Pmeds;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Tingle\Pmeds\Api\Data\QuestionsInterface as Config;
use Tingle\Pmeds\Api\QuestionsRepositoryInterface;

class Edit extends AbstractAction
{
    private $dataPersistor;

    private $repo;

    public function __construct(
        Action\Context $context,
        DataPersistorInterface $dataPersistor,
        QuestionsRepositoryInterface $repo
    ) {
        parent::__construct($context);
        $this->dataPersistor = $dataPersistor;
        $this->repo = $repo;
    }

    function execute()
    {
        /** @var \Magento\Framework\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        if ($id = $this->getRequest()->getParam('id')) {
            $model = $this->repo->getById($id);
            if (is_string($model->getOptions())) {
                $model->setOptions(json_decode($model->getOptions()));
            }
            $this->dataPersistor->set(Config::DATA_PERSISTOR_OPTIONS_KEY, $model->getOptions());
        }
        return $resultPage;
    }
}
