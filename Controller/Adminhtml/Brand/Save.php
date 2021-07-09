<?php

/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Controller\Adminhtml\Brand;

use Magento\Backend\App\Action;
use Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \Magento\Backend\App\Action
{
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Backend\Helper\Js $jsHelper
    ) {
        $this->jsHelper = $jsHelper;
        parent::__construct($context);
    }
    public function execute()
    {
        if ($this->getRequest()->getPostValue()) {
            try {
                $model = $this->_objectManager->create('Coderkube\Brand\Model\Brand');
                $data = $this->getRequest()->getPostValue();
                $inputFilter = new \Zend_Filter_Input(
                    [],
                    [],
                    $data
                );
                $data = $inputFilter->getUnescaped();
                $id = $this->getRequest()->getParam('brand_id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        throw new \Magento\Framework\Exception\LocalizedException(__('The wrong item is specified.'));
                    }
                }
                try {
                    $uploader = $this->_objectManager->create(
                        'Magento\MediaStorage\Model\File\Uploader',
                        ['fileId' => 'image']
                    );
                    $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
                    $imageAdapter = $this->_objectManager->get('Magento\Framework\Image\AdapterFactory')->create();
                    $uploader->setAllowRenameFiles(true);
                    $uploader->setFilesDispersion(true);
                    $mediaDirectory = $this->_objectManager->get('Magento\Framework\Filesystem')
                        ->getDirectoryRead(DirectoryList::MEDIA);
                    $result = $uploader->save($mediaDirectory->getAbsolutePath('brand'));
                    if ($result['error'] == 0) {
                        $data['image'] = 'brand' . $result['file'];
                    }
                } catch (\Exception $e) {
                }
                if (isset($data['image']['delete']) && $data['image']['delete'] == '1') {
                    $data['image'] = '';
                }
                if (isset($data['image']['value']) && strlen($data['image']['value']) > 1) {
                    $data['image'] = $data['image']['value'];
                }
                $links = $this->getRequest()->getPost('links');
                $links = is_array($links) ? $links : [];
                if (!empty($links) && isset($links['related'])) {
                    $products = $this->jsHelper->decodeGridSerializedInput($links['related']);
                    $data['products'] = $products;
                }
                $model->setData($data);
                $session = $this->_objectManager->get('Magento\Backend\Model\Session');
                $session->setPageData($model->getData());
                $model->save();
                $this->messageManager->addSuccess(__('You saved the item.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('brand/*/edit', ['brand_id' => $model->getId(), '_current' => true]);
                    return;
                }
                $this->_redirect('brand/*/');
                return;
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('brand_id');
                if (!empty($id)) {
                    $this->_redirect('brand/*/edit', ['brand_id' => $id]);
                } else {
                    $this->_redirect('brand/*/new');
                }
                return;
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the item data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_objectManager->get('Magento\Backend\Model\Session')->setPageData($data);
                $this->_redirect('brand/*/edit', ['brand_id' => $this->getRequest()->getParam('brand_id')]);
                return;
            }
        }
        $this->_redirect('brand/*/');
    }
}
