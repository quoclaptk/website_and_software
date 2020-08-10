<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\Category;
use Modules\Models\Product;
use Modules\Models\NewsType;
use Modules\Models\NewsCategory;
use Modules\Models\NewsMenu;
use Modules\Models\News;
use Modules\Models\Clip;

class SchemaController extends BaseController
{
    public function onConstruct()
    {
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
    }

    public function indexAction()
    {
        $products = Product::find();

        return json_encode($products->toArray());
    }

    public function afterExecuteRoute($dispatcher)
    {
        $response = new \Phalcon\Http\Response();
        $response->setHeader('Content-Type', 'application/json');
        $response->setContent($dispatcher->getReturnedValue());
        $dispatcher->setReturnedValue($response);
    }
}
