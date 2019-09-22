<?php declare(strict_types=1);
namespace Tingle\Pmeds\Block\Adminhtml\Product;

use Tingle\Pmeds\Api\Data\ConfigInterface;
use Magento\Backend\Block\Template;

class Tab extends Template
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * Tab constructor.
     *
     * @param Template\Context $context
     * @param ConfigInterface $config
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        ConfigInterface $config,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->config = $config;
    }

    /**
     * @return false|int
     */
    public function getPmedsAttributeSetId()
    {
        return $this->config->getPmedsAttributeSetId();
    }
}
