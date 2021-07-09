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

class NewAction extends Brand
{
    public function execute()
    {
        $this->_forward('edit');
    }
}
