<?php

/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Controller\Adminhtml\Brand;

class Products extends \Magento\Catalog\Controller\Adminhtml\Product
{
    protected $resultLayoutFactory;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Catalog\Controller\Adminhtml\Product\Builder $productBuilder,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory
    ) {
        parent::__construct($context, $productBuilder);
        $this->resultLayoutFactory = $resultLayoutFactory;
    }
    public function execute()
    {
        $id = $this->getRequest()->getparam('brand_id');
        $brand = $this->_objectManager->create('Coderkube\Brand\Model\Brand');
        $brand->load($id);
        $registry = $this->_objectManager->get('Magento\Framework\Registry');
        $registry->register("current_brand", $brand);
        $this->productBuilder->build($this->getRequest());
        $resultLayout = $this->resultLayoutFactory->create();
        $resultLayout->getLayout()->getBlock('coderkube_brand_brand.edit.tab.products')->setProductsRelated($this->getRequest()->getPost('products_related', null));
        return $resultLayout;
    }
}
