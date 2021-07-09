<?php
/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */
namespace Coderkube\Brand\Controller\Adminhtml\Brand;

use Coderkube\Brand\Controller\Adminhtml\Brand;

class Edit extends Brand
{
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Coderkube_Brand::save');
    }
    public function execute()
    {
        $id = $this->getRequest()->getParam('brand_id');
        $model = $this->_objectManager->create('Coderkube\Brand\Model\Brand');
        if ($id) {
            $model->load($id);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This item no longer exists.'));
                $this->_redirect('brand/*');
                return;
            }
        }
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }
        $this->_coreRegistry->register('brand_form', $model);
        $this->_initAction();
        $this->_view->getLayout()->getBlock('form_edit');
        $this->_view->renderLayout();
    }
}
