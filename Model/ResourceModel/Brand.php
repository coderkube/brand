<?php

/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Model\ResourceModel;

class Brand extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected $_date;
    protected $dateTime;
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->_date = $date;
        $this->dateTime = $dateTime;
    }
    /**
     * Initialize resource model
     */
    protected function _construct()
    {
        $this->_init('ck_brand', 'brand_id');
    }
    protected function _beforeDelete(\Magento\Framework\Model\AbstractModel $object)
    {
        $condition = ['brand_id = ?' => (int)$object->getId()];
        $this->getConnection()->delete($this->getTable('ck_brand_product'), $condition);
        return parent::_beforeDelete($object);
    }
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {
        $result = $this->checkUrlExists($object);
        if ($object->isObjectNew() && !$object->hasCreationTime()) {
            $object->setCreationTime($this->_date->gmtDate());
        }
        $object->setUpdateTime($this->_date->gmtDate());
        return parent::_beforeSave($object);
    }
    public function checkUrlExists(\Magento\Framework\Model\AbstractModel $object)
    {
        $stores = $object->getStores();
        $connection = $this->getConnection();
        $select = $connection->select()->from(
            $this->getTable('ck_brand'),
            'brand_id'
        )
            ->where(
                'url = ?',
                $object->getUrl()
            )
            ->where(
                'brand_id != ?',
                $object->getId()
            );
        $brandIds = $connection->fetchCol($select);
        if (count($brandIds) > 0) {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('This URL key already specified for other brand.')
            );
            $stores[] = '0';
            $result = $connection->fetchCol($select);
            if (count($result) > 0) {
                throw new \Magento\Framework\Exception\LocalizedException(
                    __('This URL key already specified for other brand.')
                );
            }
        }
        return $this;
    }
    protected function _afterSave(\Magento\Framework\Model\AbstractModel $object)
    {
        if (null !== ($object->getData('products'))) {
            $table = $this->getTable('ck_brand_product');
            $where = ['brand_id = ?' => (int)$object->getId()];
            $this->getConnection()->delete($table, $where);
            if ($quetionProducts = $object->getData('products')) {
                $where = ['brand_id = ?' => (int)$object->getId()];
                $this->getConnection()->delete($table, $where);
                $data = [];
                foreach ($quetionProducts as $k => $_post) {
                    $data[] = [
                        'brand_id' => (int)$object->getId(),
                        'product_id' => $k,
                        'position' =>  (int)0
                    ];
                }
                $this->getConnection()->insertMultiple($table, $data);
            }
        }
        return parent::_afterSave($object);
    }
    public function saveProduct(\Magento\Framework\Model\AbstractModel $object, $product_id = 0)
    {
        if ($object->getId() && $product_id) {
            $table = $this->getTable('ck_brand_product');
            $select = $this->getConnection()->select()->from(
                ['cp' => $table]
            )->where(
                'cp.brand_id = ?',
                (int)$object->getId()
            )->where(
                'cp.product_id = (?)',
                (int)$product_id
            )->limit(1);
            $row_product = $this->getConnection()->fetchAll($select);
            if (!$row_product) {
                $data = [];
                $data[] = [
                    'brand_id' => (int)$object->getId(),
                    'product_id' => (int)$product_id,
                    'position' =>  (int)0
                ];
                $this->getConnection()->insertMultiple($table, $data);
            }
            return true;
        }
        return false;
    }
    public function deleteBrandsByProduct($product_id = 0)
    {
        if ($product_id) {
            $condition = ['product_id = ?' => (int)$product_id];
            $this->getConnection()->delete($this->getTable('ck_brand_product'), $condition);
            return true;
        }
        return false;
    }
    public function getBrandIdByName($brand_name = '')
    {
        if ($brand_name) {
            $brand_id = null;
            $table = $this->getTable('ck_brand');
            $select = $this->getConnection()->select()->from(
                ['cp' => $table]
            )->where(
                'cp.name = ?',
                $brand_name
            )->limit(1);
            $row_brand = $this->getConnection()->fetchAll($select);
            if ($row_brand) {
                $brand_id = isset($row_brand[0]['brand_id']) ? (int)$row_brand[0]['brand_id'] : null;
            }
            return $brand_id;
        }
        return null;
    }
    protected function _afterLoad(\Magento\Framework\Model\AbstractModel $object)
    {
        if ($id = $object->getId()) {
            $connection = $this->getConnection();
            $select = $connection->select()
                ->from($this->getTable('ck_brand_product'))
                ->where(
                    'brand_id = ' . (int)$id
                );
            $products = $connection->fetchAll($select);
            $object->setData('products', $products);
        }
        return parent::_afterLoad($object);
    }
}
