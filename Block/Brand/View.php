<?php
/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */
namespace Coderkube\Brand\Block\Brand;

class View extends \Magento\Framework\View\Element\Template
{
    protected $_coreRegistry = null;
    protected $_catalogLayer;
    protected $_storeManager;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\Layer\Resolver $layerResolver,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_catalogLayer = $layerResolver->get();
        $this->_coreRegistry = $registry;
        $this->_storeManager = $storeManager;
        parent::__construct($context, $data);
    }
     
    public function _prepareLayout()
    {
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $baseUrl = $this->_storeManager->getStore()->getBaseUrl();
        $route = 'brand';
        $brand = $this->getBrand();

        if ($breadcrumbsBlock) {

            $breadcrumbsBlock->addCrumb(
                'home',
                [
                'label' => __('Home'),
                'title' => __('Home Page'),
                'link' => $baseUrl
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'coderkube',
                [
                'label' => __('All Brand'),
                'title' => __('All Brand'),
                'link' => $baseUrl.$route
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'brand',
                [
                'label' => $brand->getName(),
                'title' => $brand->getName(),
                'link' => ''
                ]
            );
        }
        $page_title = $brand->getName();
        $this->pageConfig->getTitle()->set($page_title);
        return parent::_prepareLayout();
    }

    public function getBrand()
    {
        $brand = $this->_coreRegistry->registry('current_brand');
        if ($brand) {
            $this->setData('current_brand', $brand);
        }
        return $brand;
    }
   

    public function getBrandProductListHtml()
    {
        return $this->getChildHtml('brandproduct_list');
    }



    protected function _beforeToHtml()
    {
        return parent::_beforeToHtml();
    }
}
