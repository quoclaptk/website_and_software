<?php

namespace Modules\Backend\Controllers;

class PartialsController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Cấu hình';
    }

    public function navSettingAction()
    {
        $this->view->setTemplateBefore('nav_setting');
        $this->view->pick($this->_getControllerName() . '/nav_setting');
    }
}
