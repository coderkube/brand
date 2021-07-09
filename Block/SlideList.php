<?php
/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */
namespace Coderkube\Brand\Block;

use Magento\Framework\View\Element\Template\Context;
use Coderkube\Brand\Model\BrandFactory;

class SlideList extends \Magento\Framework\View\Element\Template
{
    protected $_storeManager;
    protected $_brand;
    public function __construct(
        Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        BrandFactory $brand
    ) {
        $this->_brand = $brand;
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getMediaUrl()
    {
        $mediaUrl = $this->_storeManager->getStore()
                     ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $mediaUrl;
    }
 
    public function _prepareLayout()
    {
       
        return parent::_prepareLayout();
    }

 
    public function getBrandCollection()
    {
        $brand = $this->_brand->create();
        $collection = $brand->getCollection();
        $collection->addFieldToFilter('status', '1');
        $collection->setOrder('position', 'ASC');
        return $collection;
    }
}
