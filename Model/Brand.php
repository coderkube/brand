<?php

/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Model;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\App\ObjectManager;

class Brand extends \Magento\Framework\Model\AbstractModel
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 0;
    protected $_url;
    protected $_productCollectionFactory;
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Coderkube\Brand\Model\ResourceModel\Brand $resource = null,
        \Coderkube\Brand\Model\ResourceModel\Brand\Collection $resourceCollection = null,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }
    protected function _construct()
    {
        $this->_init('Coderkube\Brand\Model\ResourceModel\Brand');
        $cacheManager = ObjectManager::getInstance()->get('\Magento\Framework\App\Cache\Manager');
        $types = ['config', 'layout', 'block_html', 'collections', 'reflection', 'db_ddl', 'eav', 'config_integration', 'config_integration_api', 'full_page', 'translate', 'config_webservice'];
        $cacheManager->flush($types);
    }
    public function getAvailableStatuses()
    {
        return [self::STATUS_ENABLED => _('Enabled'), self::STATUS_DISABLED => _('Disabled')];
    }
    public function getProductCollection()
    {
        $collection = $this->_productCollectionFactory->create()->addAttributeToSelect('*')->addAttributeToFilter('coderkube_brand', ['eq' => $this->getId()]);
        return $collection;
    }
}
