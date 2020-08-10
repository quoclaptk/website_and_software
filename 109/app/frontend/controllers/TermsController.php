<?php

namespace Nginx\Controllers;

class TermsController extends BaseController
{
    public function indexAction()
    {
        $this->view->setTemplateBefore('public');
    }
}
