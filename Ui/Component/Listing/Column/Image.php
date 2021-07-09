<?php

/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\View\Asset\Repository;

class Image extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME = 'image';
    const ALT_FIELD = 'name';
    protected $storeManager;
    private $_backendUrl;
    private $assetRepo;
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        Repository $assetRepo,
        \Magento\Backend\Model\UrlInterface $backendUrl,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->storeManager = $storeManager;
        $this->assetRepo = $assetRepo;
        $this->_backendUrl = $backendUrl;
    }
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            $path = $this->storeManager->getStore()->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            );
            $baseImage = $this->assetRepo->getUrl('Coderkube_Brand::images/place.png');
            foreach ($dataSource['data']['items'] as &$item) {
                if ($item['image']) {
                    $item[$fieldName . '_src'] = $path . $item['image'];
                    $item[$fieldName . '_alt'] = $item['image'];
                    $item[$fieldName . '_orig_src'] = $path . $item['image'];
                } else {
                    $item[$fieldName . '_src'] = $baseImage;
                    $item[$fieldName . '_alt'] = 'Place Holder';
                    $item[$fieldName . '_orig_src'] = $baseImage;
                }
                $item[$fieldName . '_link'] = $this->_backendUrl->getUrl(
                    'brand/brand/edit/',
                    ['brand_id' => $item['brand_id']]
                );
            }
        }
        return $dataSource;
    }
}
