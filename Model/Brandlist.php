<?php

/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Model;

class Brandlist extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    protected $_brand;
    public function __construct(
        \Coderkube\Brand\Model\Brand $brand
    ) {
        $this->_brand = $brand;
    }
    public function getAllOptions()
    {
        $brands = $this->_brand->getCollection()->addFieldToFilter('status', 1);
        $attributesOption = [];
        foreach ($brands as $brand) {
            $attributesOption[] = [
                'label' => $brand->getName(),
                'value' => $brand->getId()
            ];
        }
        return $attributesOption;
    }
}
