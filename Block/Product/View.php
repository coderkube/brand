<?php

namespace Coderkube\Brand\Block\Product;

use Magento\Customer\Model\Context as CustomerContext;

class View extends \Magento\Framework\View\Element\Template
{
    protected $_brandCollection;
    protected $_coreRegistry = null;
    protected $_resource;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Registry $registry,
        //\Coderkube\Brand\Helper\Data $brandHelper,
        \Coderkube\Brand\Model\Brand $brandCollection,
        \Magento\Framework\App\ResourceConnection $resource,
        array $data = []
    ) {
        $this->_brandCollection = $brandCollection;
        $this->_coreRegistry = $registry;
        $this->_resource = $resource;
        parent::__construct($context, $data);
    }
    public function getProduct()
    {
        return $this->_coreRegistry->registry('current_product');
    }
    public function getBrandCollection()
    {
        $product = $this->getProduct();
        $connection = $this->_resource->getConnection();
        $table_name = $this->_resource->getTableName('ck_brand_product');
        $brandIds = $connection->fetchCol(" SELECT brand_id FROM " . $table_name . " WHERE product_id = " . $product->getId());
        if ($brandIds || count($brandIds) > 0) {
            $collection = $this->_brandCollection->getCollection()
                ->addFieldToFilter('status', 1);
            $product->setOrder('product_position', 'ASC');
            $collection->getSelect()->where('brand_id IN (?)', $brandIds);
            return $collection;
        }
        return false;
    }
}
