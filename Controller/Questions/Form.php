<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Questions;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Tingle\Pmeds\Api\ProductQuestionsRepositoryInterface;
use Tingle\Pmeds\Block\Form\Form as QuestionsForm;
use Tingle\Pmeds\Setup\InstallData;

class Form extends Action
{
    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    private $productQuestionsRepository;

    /**
     * @var AttributeSetRepositoryInterface
     */
    private $attributeSetRepository;

    public function __construct(
        Context $context,
        LayoutInterface $layout,
        ProductRepositoryInterface $productRepository,
        ProductQuestionsRepositoryInterface $productQuestionsRepository,
        AttributeSetRepositoryInterface $attributeSetRepository
    ) {
        parent::__construct($context);
        $this->layout = $layout;
        $this->productRepository = $productRepository;
        $this->productQuestionsRepository = $productQuestionsRepository;
        $this->attributeSetRepository = $attributeSetRepository;
    }

    /**
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData(['hasForm' => false]);

        if (!$this->getRequest()->isAjax() || !$this->getRequest()->getParam('sku')) {
            return $resultJson;
        }

        try {
            $product = $this->productRepository->get($this->getRequest()->getParam('sku'));
            if ($this->canBuildForm($product)) {
                $resultJson->setData([
                    'hasForm' => true,
                    'formHtml' => $this->buildBlock($product)
                ]);
                return $resultJson;
            }
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
        }

        return $resultJson;
    }

    /**
     * Check if current product belongs to the 'P-meds' attribute set and has questions selected
     * //TODO:Add check if product has questions at all.
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return boolean
     */
    private function canBuildForm($product)
    {
        try {
            $attributeSetName = $this->attributeSetRepository->get($product->getAttributeSetId())->getAttributeSetName();

            $isPmedsAttributeSet = $attributeSetName === InstallData::ATTRIBUTE_SET_NAME;
            $hasQuestions = $this->productQuestionsRepository->getAllProductQuestionsMetaData($product);

            if ($isPmedsAttributeSet && $hasQuestions) {
                return true;
            }
        } catch (\Exception $e) {
        }

        return false;
    }

    /**
     * Build questions form and render it
     *
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return string
     */
    private function buildBlock($product)
    {
        $formBlockHtml = $this->layout->createBlock(
            QuestionsForm::class,
            'tingle.questions.form',
            [
                'data' => [
                    \Tingle\Pmeds\Block\Form\Form::PRODUCT_DATA_KEY => $product
                ]
            ]
        )->toHtml();

        return $formBlockHtml;
    }
}
