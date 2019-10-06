<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Product\View;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Pmeds extends Template
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var ConfigInterface
     */
    private $config;

    /**
     * Pmeds constructor.
     *
     * @param Template\Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        ConfigInterface $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->config = $config;
    }

    /**
     * Get pmeds uploaded image url
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getFileLinkUrl()
    {
        $storeMediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $imagePath = $this->_scopeConfig->getValue(ConfigInterface::XML_PATH_ICON_UPLOAD_PATH);

        return $storeMediaUrl . 'pmeds' . DIRECTORY_SEPARATOR . $imagePath;
    }

    /**
     * Get info help text
     *
     * @return string
     */
    public function getInfoHelpText()
    {
        return $this->_scopeConfig->getValue(ConfigInterface::XML_PATH_INFO_HELP_TEXT);
    }

    /**
     * Is product at pmeds attribute set or not
     *
     * @return bool
     */
    public function isProductInPmedsAttrSet()
    {
        if ($this->getProduct()->getAttributeSetId() === $this->config->getPmedsAttributeSetId()) {
            return true;
        }

        return false;
    }

    public function getProduct()
    {
        return $this->registry->registry('product');
    }

    /**
     * Can show at product page?
     *
     * @return bool
     */
    public function isEnabledAtProductPage()
    {
        $isEnabledHelpInfoText   = $this->_scopeConfig->isSetFlag(ConfigInterface::XML_PATH_INFO_HELP_TEXT_ENABLE);
        $isEnabledAtProductPage  = $this->_scopeConfig->isSetFlag(ConfigInterface::XML_PATH_ENABLE_AT_PRODUCT_PAGE);
        $isProductInPmedsAttrSet = $this->isProductInPmedsAttrSet();

        $isAllowed = ($isEnabledAtProductPage && $isEnabledHelpInfoText && $isProductInPmedsAttrSet);

        return $isAllowed;
    }
}
