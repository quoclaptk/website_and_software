<?php namespace Modules\Frontend\Controllers;

class AboutController extends BaseController
{
    public function indexAction()
    {
        $this->view->setTemplateBefore('public');
    }
}
