<?php namespace Modules\Frontend\Controllers;

class ErrorController extends BaseController
{
    public function show404Action()
    {
        $this->view->setTemplateBefore('public');
        $this->response->setStatusCode(404, 'Not Found');
        //$this->view->pick('404/404');
    }
}
