<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Adminhtml\Pmeds;

use Magento\Backend\App\Action;
use Tingle\Pmeds\Api\Data\QuestionsInterfaceFactory;
use Tingle\Pmeds\Api\QuestionsRepositoryInterface;

class Save extends AbstractAction
{
    private $modelFactory;

    private $repository;

    public function __construct(
        Action\Context $context,
        QuestionsInterfaceFactory $modelFactory,
        QuestionsRepositoryInterface $questionsRepository
    ) {
        parent::__construct($context);
        $this->repository = $questionsRepository;
        $this->modelFactory = $modelFactory;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    function execute()
    {
        $data = $this->getRequest()->getPostValue();
        /** @var \Tingle\Pmeds\Api\Data\QuestionsInterface $model */
        $model = $this->modelFactory->create();

        $model->setTitle($data['title']);

        try {
            $this->repository->save($model);
            $this->messageManager->addSuccessMessage(__('Question successfully saved.'));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        $redirect = $this->resultRedirectFactory->create();
        return $redirect->setPath('tingle/pmeds/index');
    }
}
