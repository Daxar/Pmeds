<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Product\View\Listing;

use Magento\Framework\View\Element\Template;
use Tingle\Pmeds\Api\Data\ConfigInterface;
use Tingle\Pmeds\Block\Form\Form;

class Pmeds extends Template
{
    protected $_template = 'Tingle_Pmeds::product/list/view/pmeds.phtml';

    /**
     * Get uploaded image url
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

    public function getProduct()
    {
        return $this->getData(Form::PRODUCT_DATA_KEY);
    }
}
