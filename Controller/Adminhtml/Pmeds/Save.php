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
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface
     */
    function execute()
    {
        $redirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($id = $this->getRequest()->getParam('id')) {
            $model = $this->repository->getById($id);
        } else {
            $model = $this->modelFactory->create();
        }

        /** @var \Tingle\Pmeds\Model\Questions $model */
        $model->setTitle($data['title'])
            ->setSortOrder($data['sort_order'])
            ->setRequired($data['required'])
            ->setTypeId($data['type_id'])
            ->setOptions(!empty($data['options']) ? $data['options'] : null)
            ->setAnswer(!empty($data['answer']) ? $data['answer'] : null);

        try {
            $this->repository->save($model);
            $this->messageManager->addSuccessMessage(__('Question successfully saved.'));
            if ($this->getRequest()->getParam('back')) {
                return $redirect->setPath(self::BASE_ACTION_PATH . '/edit', ['id' => $model->getId()]);
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $redirect->setPath(self::BASE_ACTION_PATH);
    }
}
