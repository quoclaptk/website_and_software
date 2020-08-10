<?php

namespace Nginx\Controllers;

class PrivacyController extends BaseController
{
    public function indexAction()
    {
        $this->view->setTemplateBefore('public');
    }
}
