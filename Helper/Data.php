<?php
/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_filterProvider;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider
    ) {
        parent::__construct($context);
        $this->_filterProvider = $filterProvider;
    }
    public function getConfig($config)
    {
            return $this->scopeConfig->getValue(
                $config,
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE
            );
    }

    public function description($string)
    {
        $html = $this->_filterProvider->getPageFilter()->filter($string);
        return $html;
    }
}
