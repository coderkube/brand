<?php

/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Model\Layer;

use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\Resource;

class Brand extends \Magento\Catalog\Model\Layer
{
    public function getProductCollection()
    {
        $brand = $this->getBrand();
        if (isset($this->_productCollections[$brand->getId()])) {
            $collection = $this->_productCollections;
        } else {
            $collection = $brand->getProductCollection();
            $this->prepareProductCollection($collection);
            $this->_productCollections[$brand->getId()] = $collection;
        }
        return $collection;
    }
    public function getBrand()
    {
        $brand = $this->getData('current_brand');
        if ($brand === null) {
            $brand = $this->registry->registry('current_brand');
            if ($brand) {
                $this->setData('current_brand', $brand);
            }
        }
        return $brand;
    }
}
