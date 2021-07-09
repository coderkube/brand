<?php
/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Block\Adminhtml\Brand\Edit;

use Magento\Backend\Block\Widget\Tabs as WidgetTabs;

class Tabs extends WidgetTabs
{
    
    protected function _construct()
    {
        parent::_construct();
        $this->setId('coderkube_brand_brand_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Brand Information'));
    }

    protected function _beforeToHtml()
    {
        $this->addTab(
            'products',
            [
               'label' => __('Products Tab'),
               'url' => $this->getUrl('brand/brand/products', ['_current' => true]),
               'class' => 'ajax'
            ]
        );
        return parent::_beforeToHtml();
    }
}
