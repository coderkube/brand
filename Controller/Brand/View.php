<?php
/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */
namespace Coderkube\Brand\Controller\Brand;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Controller\AccountInterface;
use Coderkube\Brand\Model\Layer\Resolver;

class View extends Action
{
    
    protected $_request;
    protected $_response;
    protected $_coreRegistry = null;
    private $layerResolver;
    private $pageFactory;
    protected $_brandModel;


    public function __construct(
        \Coderkube\Brand\Model\Brand $brandModel,
        Context $context,
        \Magento\Framework\Registry $coreRegistry,
        Resolver $layerResolver,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
        $this->_brandModel = $brandModel;
        $this->layerResolver = $layerResolver;
        $this->_coreRegistry = $coreRegistry;
    }
    
    public function _initBrand()
    {
        $brandId = (int)$this->getRequest()->getParam('brand_id', false);
        $brand = $this->_brandModel->load($brandId);
        $this->_coreRegistry->register('current_brand', $brand);
        return $brand;
    }

   
    public function execute()
    {
          $brand = $this->_initBrand();
        if ($brand) {
            $this->layerResolver->create('ckbrand');
            $page = $this->pageFactory->create();
        }
       
        $page->getConfig()->addBodyClass('page-productslist')
            ->addBodyClass('ckbrand-' . $this->_brandModel->getUrl());
            return $page;
    }
}
