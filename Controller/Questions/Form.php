<?php declare(strict_types=1);
namespace Tingle\Pmeds\Controller\Questions;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\LayoutInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Tingle\Pmeds\Block\Form\Form as QuestionsForm;
use Tingle\Pmeds\Setup\InstallData;

// TODO: Beautify this class
class Form extends Action
{
    private $layout;

    private $productRepository;

    private $attributeSetRepository;

    public function __construct(
        Context $context,
        LayoutInterface $layout,
        ProductRepositoryInterface $productRepository,
        AttributeSetRepositoryInterface $attributeSetRepository
    ) {
        parent::__construct($context);
        $this->layout = $layout;
        $this->productRepository = $productRepository;
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
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function canBuildForm($product)
    {
        $attributeName = $this->attributeSetRepository->get($product->getAttributeSetId())->getAttributeSetName();

        if ($attributeName === InstallData::ATTRIBUTE_SET_NAME) {
            return true;
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
                    'product' => $product
                ]
            ]
        )->toHtml();

        return $formBlockHtml;
    }
}
