<?php

/**
 * CoderkubeTeam
 * Copyright(C) 06/2020 CoderkubeTeam <support@coderkube.com>
 * @package Coderkube_Brand
 * @copyright Copyright(C) 06/2020 CoderkubeTeam (https://coderkube.com/)
 * @author CoderkubeTeam <support@coderkube.com>
 */

namespace Coderkube\Brand\Block\Adminhtml\Brand\Edit\Tab;

use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Coderkube\Brand\Model\Source\Status;

class Main extends Generic implements TabInterface
{
    protected $_wysiwygConfig;
    protected $_brandStatus;
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Status $brandStatus,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig,
        array $data = []
    ) {
        $this->_brandStatus = $brandStatus;
        $this->_wysiwygConfig = $wysiwygConfig;
        parent::__construct($context, $registry, $formFactory, $data);
    }
    public function getTabLabel()
    {
        return __('Brand Info');
    }
    public function getTabTitle()
    {
        return __('Brand Info');
    }
    public function canShowTab()
    {
        return true;
    }
    public function isHidden()
    {
        return false;
    }
    protected function _prepareForm()
    {
        $model = $this->_coreRegistry->registry('brand_form');
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('brand_');
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
        );
        if ($model->getId()) {
            $fieldset->addField(
                'brand_id',
                'hidden',
                ['name' => 'brand_id']
            );
        }
        $fieldset->addField(
            'name',
            'text',
            [
                'name'        => 'name',
                'label'    => __('Brand Name'),
                'required'     => true
            ]
        );
        $fieldset->addField(
            'url',
            'text',
            [
                'name'      => 'url',
                'label'     => __('Brand URL'),
                'required' => true,
                'note' => 'Please add your brand url here (FOR EX: sony/)'
            ]
        );
        $fieldset->addField(
            'image',
            'image',
            [
                'name'      => 'image',
                'label'     => __('Brand Image')
            ]
        );
        $fieldset->addField(
            'description',
            'editor',
            [
                'name' => 'description',
                'label' => __('Description'),
                'title' => __('Description'),
                'style' => 'height:26em;',
                'required' => true,
                'config'    => $this->_wysiwygConfig->getConfig(),
                'wysiwyg' => true
            ]
        );
        $fieldset->addField(
            'status',
            'select',
            [
                'name' => 'status',
                'label' => __('Status'),
                'title' => __('Status'),
                'options' => $this->_brandStatus->getOptionArray(),
                'required' => true
            ]
        );
        $fieldset->addField(
            'position',
            'text',
            [
                'name'      => 'position',
                'label'     => __('Position'),
                'required' => false
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
        return parent::_prepareForm();
    }
}
