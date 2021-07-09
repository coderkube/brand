<?php

/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Block\Adminhtml\Brand;

use Magento\Backend\Block\Widget\Form\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\Registry;

class Edit extends Container
{
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
    /**
     * @param Context $context
     * @param Registry $registry
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'brand_id';
        $this->_controller = 'adminhtml_brand';
        $this->_blockGroup = 'Coderkube_Brand';
        parent::_construct();
    }
    public function getHeaderText()
    {
        $formRegistry = $this->_coreRegistry->registry('brand_form');
        if ($formRegistry->getId()) {
            return __("Edit Brand '%1'", $this->escapeHtml($formRegistry->getName()));
        } else {
            return __('Add Data');
        }
    }
}
