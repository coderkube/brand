<?php

/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Block;

class HeaderLink extends \Magento\Framework\View\Element\Html\Link
{
    public function _toHtml()
    {
        if (!$this->_scopeConfig->isSetFlag('coderkubebrand/general_settings/enable') ||
            !$this->_scopeConfig->isSetFlag('coderkubebrand/general_settings/headerlink')
        ) {
            return '';
        }
        return parent::_toHtml();
    }
}
