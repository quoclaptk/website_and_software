<?php

namespace Modules\Frontend\Controllers;

use Phalcon\Mvc\View;

class RobotsController extends BaseController
{
    public function onConstruct()
    {
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
    }

    public function indexAction()
    {
        $file = file_get_contents('robots-sample.txt');
        return $file;
    }

    public function afterExecuteRoute($dispatcher)
    {
        $response = new \Phalcon\Http\Response();
        $response->setHeader('Content-Type', 'text/plain');
        $response->setContent($dispatcher->getReturnedValue());
        $dispatcher->setReturnedValue($response);
    }
}
