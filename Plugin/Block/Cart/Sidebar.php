<?php declare(strict_types=1);
namespace Tingle\Pmeds\Plugin\Block\Cart;

use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use Magento\Framework\App\Request\Http as Request;
use Tingle\Pmeds\Api\Data\ConfigInterface as Config;

class Sidebar
{
    /**
     * @var ScopeConfig
     */
    private $config;

    /**
     * @var Request
     */
    private $request;

    /**
     * Sidebar constructor.
     *
     * @param ScopeConfig $config
     * @param Request $request
     */
    public function __construct(ScopeConfig $config, Request $request)
    {
        $this->config = $config;
        $this->request = $request;
    }

    /**
     * Add questionnaire configuration to the frontend part of the application
     *
     * @param \Magento\Checkout\Block\Cart\Sidebar $subject
     * @param array $result
     * @return array
     * @throws \Exception
     */
    public function afterGetConfig(\Magento\Checkout\Block\Cart\Sidebar $subject, $result)
    {
        try {
            $result['pmeds_config'] = $this->getConfig();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage()); // TODO: Remove once extension is finished
        }
        return $result;
    }

    private function getConfig()
    {
        $result = [];

        $result['enabled_at_product_page']  = $this->canShowAtProductPage();
        $result['enabled_at_category_page'] = $this->canShowAtCategoryPage();

        if ((boolean)$result['enabled_at_category_page'] || (boolean)$result['enabled_at_product_page']) {
            $result['info_title']               = $this->getValue(Config::XML_PATH_INFO_TITLE);
            $result['info_help_text']           = $this->getValue(Config::XML_PATH_INFO_HELP_TEXT);
            $result['questionnaire_title_text'] = $this->getValue(Config::XML_PATH_QUESTIONNAIRE_TITLE);
            $result['questionnaire_intro_text'] = $this->getValue(Config::XML_PATH_QUESTIONNAIRE_INTRO);
            $result['questionnaire_pass_text']  = $this->getValue(Config::XML_PATH_QUESTIONNAIRE_PASS_TEXT);
            $result['questionnaire_fail_text']  = $this->getValue(Config::XML_PATH_QUESTIONNAIRE_FAIL_TEXT);
            $result['add_to_cart_text']         = $this->getValue(Config::XML_PATH_ADD_TO_CART_TEXT);
        }

        return $result;
    }

    /**
     * @return boolean
     */
    private function canShowAtCategoryPage()
    {
        $category = $this->isEnabledAndMatches('catalog_category_view', $this->getFlag(Config::XML_PATH_ENABLE_AT_CATEGORY_PAGE));
        $search   = $this->isEnabledAndMatches('catalogsearch_result_index', $this->getFlag(Config::XML_PATH_ENABLE_AT_CATEGORY_PAGE));

        return ($search || $category);
    }

    /**
     * @return boolean
     */
    private function canShowAtProductPage()
    {
        return $this->isEnabledAndMatches('catalog_product_view', $this->getFlag(Config::XML_PATH_ENABLE_AT_PRODUCT_PAGE));
    }

    /**
     * @param string $target
     * @param boolean $isEnabled
     * @return boolean
     */
    private function isEnabledAndMatches($target, $isEnabled)
    {
        if (!$this->getFlag(Config::XML_PATH_ENABLE)) {
            return false;
        }

        if ($target === $this->request->getFullActionName() && (boolean)$isEnabled) {
            return true;
        }

        return false;
    }

    /**
     * @param string $xmlPath
     * @return string|null
     */
    private function getFlag($xmlPath)
    {
        return $this->config->isSetFlag($xmlPath);
    }

    /**
     * @param string $xmlPath
     * @return string|null
     */
    private function getValue($xmlPath)
    {
        return $this->config->getValue($xmlPath);
    }
}
