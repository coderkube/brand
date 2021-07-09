<?php
/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */
namespace Coderkube\Brand\Controller;

use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Event\ManagerInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Url;

class Router implements RouterInterface
{
    protected $actionFactory;
    protected $eventManager;
    protected $response;
    protected $dispatched;
    protected $_brandCollection;
    protected $_brandHelper;
    protected $storeManager;

    public function __construct(
        ActionFactory $actionFactory,
        ResponseInterface $response,
        ManagerInterface $eventManager,
        \Coderkube\Brand\Model\Brand $brandCollection,
        \Coderkube\Brand\Helper\Data $brandHelper,
        StoreManagerInterface $storeManager
    ) {
        $this->actionFactory = $actionFactory;
        $this->eventManager = $eventManager;
        $this->response = $response;
        $this->_brandHelper = $brandHelper;
        $this->_brandCollection = $brandCollection;
        $this->storeManager = $storeManager;
    }

    public function match(RequestInterface $request)
    {
        $_brandHelper = $this->_brandHelper;
        if (!$this->dispatched) {
            $url = trim($request->getPathInfo(), '/');
            $origUrl = $url;
            $condition = new DataObject(['url' => $url, 'continue' => true]);
            $this->eventManager->dispatch(
                'coderkube_brand_controller_router_match_before',
                ['router' => $this, 'condition' => $condition]
            );
            $url = $condition->getUrl();
            if ($condition->getRedirectUrl()) {
                $this->response->setRedirect($condition->getRedirectUrl());
                $request->setDispatched(true);
                return $this->actionFactory->create(
                    'Magento\Framework\App\Action\Redirect',
                    ['request' => $request]
                );
            }
            if (!$condition->getContinue()) {
                return null;
            }
            $route = 'brand';
            if ($url == $route) {
                $request->setModuleName('brand')
                    ->setControllerName('index')
                    ->setActionName('index');
                $request->setAlias(Url::REWRITE_REQUEST_PATH_ALIAS, $url);
                $this->dispatched = true;
                return $this->actionFactory->create(
                    'Magento\Framework\App\Action\Forward',
                    ['request' => $request]
                );
            }
            $identifiers = explode('/', $url);
            if (count($identifiers) == 2 || count($identifiers) == 1) {
                if (count($identifiers) == 2) {
                    $brandUrl = $identifiers[1];
                }
                if (count($identifiers) == 1) {
                    $brandUrl = $identifiers[0];
                }
                $brand = $this->_brandCollection->getCollection()
                    ->addFieldToFilter('status', ['eq' => 1])
                    ->addFieldToFilter('url', ['eq' => $brandUrl])
                    //->addStoreFilter($this->storeManager->getStore()->getId())
                    ->getFirstItem();
                if ($brand && $brand->getId()) {
                    $request->setModuleName('brand')
                        ->setControllerName('brand')
                        ->setActionName('view')
                        ->setParam('brand_id', $brand->getId());
                    $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $origUrl);
                    $request->setDispatched(true);
                    $this->dispatched = true;
                    return $this->actionFactory->create(
                        'Magento\Framework\App\Action\Forward',
                        ['request' => $request]
                    );
                }
            }
        }
    }
}
