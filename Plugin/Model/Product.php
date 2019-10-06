<?php declare(strict_types=1);
namespace Tingle\Pmeds\Plugin\Model;

use Magento\Catalog\Block\Product\ListProduct;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\View\LayoutInterface;
use Magento\Catalog\Model\Product as OriginalProduct;
use mysql_xdevapi\Exception;
use Tingle\Pmeds\Block\Form\Form;
use Tingle\Pmeds\Block\Product\View\Listing\Pmeds;
use Tingle\Pmeds\Api\Data\ConfigInterface;

class Product
{
    /**
     * @var ScopeConfigInterface
     */
    private $config;

    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * @var ConfigInterface
     */
    private $pmedsConfig;

    /**
     * Product constructor.
     *
     * @param ScopeConfigInterface $config
     * @param LayoutInterface $layout
     * @param ConfigInterface $pmedsConfig
     */
    public function __construct(
        ScopeConfigInterface $config,
        LayoutInterface $layout,
        ConfigInterface $pmedsConfig
    ) {
        $this->config = $config;
        $this->layout = $layout;
        $this->pmedsConfig = $pmedsConfig;
    }

    /**
     * Concat pmeds image block to product details block
     *
     * @param ListProduct $subject
     * @param \Closure $proceed
     * @param OriginalProduct $product
     * @return mixed|string
     */
    public function aroundGetProductDetailsHtml(ListProduct $subject, \Closure $proceed, OriginalProduct $product)
    {
        try {
            if ($this->isProductInPmedsAttrSet($product) && $this->isEnabled()) {
                $result = $proceed($product);
                $pmedsBlock = $this->buildBlock($product);

                return $result . $pmedsBlock;
            }
        } catch (\Exception $e) {
        }

        return $proceed($product);
    }

    /**
     * @return bool
     */
    private function isEnabled()
    {
        $isEnabledHelpInfoText = $this->config->isSetFlag(ConfigInterface::XML_PATH_INFO_HELP_TEXT_ENABLE);
        $isEnabledAtListingPage  = $this->config->isSetFlag(ConfigInterface::XML_PATH_ENABLE_AT_CATEGORY_PAGE);

        $isAllowed = ($isEnabledAtListingPage && $isEnabledHelpInfoText);

        return $isAllowed;
    }

    /**
     * Check if current product is in 'pmeds' attribute set or not
     *
     * @param $product
     * @return bool
     */
    private function isProductInPmedsAttrSet($product)
    {
        if ($product->getAttributeSetId() === $this->pmedsConfig->getPmedsAttributeSetId()) {
            return true;
        }

        return false;
    }

    /**
     * Build image block html
     *
     * @param $product
     * @return string
     */
    private function buildBlock($product)
    {
        $formBlockHtml = $this->layout->createBlock(
            Pmeds::class,
            '',
            [
                'data' => [
                    Form::PRODUCT_DATA_KEY => $product
                ]
            ]
        )->toHtml();

        return $formBlockHtml;
    }
}
