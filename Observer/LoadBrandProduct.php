<?php

/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Observer;

use Magento\Framework\Event\ObserverInterface;

class LoadBrandProduct implements ObserverInterface
{
    /**
     * Catalog data
     *
     * @var \Magento\Catalog\Helper\Data
     */
    protected $catalogData;
    /**
     * @param \Magento\Catalog\Helper\Data $catalogData
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        $this->_resource = $resource;
    }
    /**
     * Checking whether the using static urls in WYSIWYG allowed event
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product = $observer->getProduct();
        $connection = $this->_resource->getConnection();
        $table_name = $this->_resource->getTableName('ck_brand_product');
        if ($product->getId()) {
            $productIds = $connection->fetchCol(" SELECT brand_id FROM " . $table_name . " WHERE product_id = " . $product->getId());
            $product->setData('coderkube_brand', implode(',', $productIds));
        }
    }
}
