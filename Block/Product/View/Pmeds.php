<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Product\View;

use Magento\Framework\View\Element\Template;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class Pmeds extends Template
{
    public function __construct(
        Template\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    public function stuff()
    {
        return ConfigInterface::XML_PATH_ENABLE;
    }

    public function getFileLinkUrl()
    {
        $storeMediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        $imagePath = $this->_scopeConfig->getValue(ConfigInterface::XML_PATH_ICON_UPLOAD_PATH);

        return $storeMediaUrl . 'pmeds' . DIRECTORY_SEPARATOR . $imagePath;
    }

    public function getInfoHelpText()
    {
        return $this->_scopeConfig->getValue(ConfigInterface::XML_PATH_INFO_HELP_TEXT);
    }

    public function isEnabledAtProductPage()
    {
        $isEnabledAtProductPage = $this->_scopeConfig->isSetFlag(ConfigInterface::XML_PATH_INFO_HELP_TEXT_ENABLE);
        $isEnabledHelpInfoText  = $this->_scopeConfig->isSetFlag(ConfigInterface::XML_PATH_ENABLE_AT_PRODUCT_PAGE);

        $isAllowed = ($isEnabledAtProductPage && $isEnabledHelpInfoText);

        return $isAllowed;
    }
}
