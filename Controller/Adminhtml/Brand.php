<?php
/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */
namespace Coderkube\Brand\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Coderkube\Brand\Model\BrandFactory;

abstract class Brand extends Action
{
    
    protected $_coreRegistry;

    protected $_resultPageFactory;
    protected $_brandFactory;
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        PageFactory $resultPageFactory,
        BrandFactory $brandFactory
    ) {
        parent::__construct($context);
        $this->_coreRegistry = $coreRegistry;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_brandFactory = $brandFactory;
    }

    protected function _initAction()
    {
        $this->_view->loadLayout();
        $this->_setActiveMenu('Coderkube_Brand::manage_brand')->_addBreadcrumb(__('Brand-Data'), __('Brand-Data'));
        return $this;
    }
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Coderkube_Brand::manage_brand');
    }
}
