<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Adminhtml\Pmeds;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action;
use Magento\Ui\Component\MassAction\Filter;
use Tingle\Pmeds\Api\QuestionsRepositoryInterface;
use Tingle\Pmeds\Model\ResourceModel\Questions\CollectionFactory;


class MassDelete extends AbstractAction
{
    /**
     * @var Filter
     */
    private $filter;

    /**
     * @var QuestionsRepositoryInterface
     */
    private $questionsRepository;

    /**
     * @var CollectionFactory
     */
    private $collectionFactory;

    /**
     * MassDelete constructor.
     *
     * @param Action\Context $context
     * @param Filter $filter
     * @param QuestionsRepositoryInterface $questionsRepository
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        Action\Context $context,
        Filter $filter,
        QuestionsRepositoryInterface $questionsRepository,
        CollectionFactory $collectionFactory
    ) {
        $this->filter = $filter;
        $this->questionsRepository = $questionsRepository;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $collection->getSize();

            foreach ($collection as $model) {
                $this->questionsRepository->delete($model);
            }

            $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
        } catch (\Exception $exception) {
            $this->messageManager->addErrorMessage($exception->getMessage());
        }
        return $resultRedirect->setPath(AbstractAction::BASE_ACTION_PATH);
    }
}
