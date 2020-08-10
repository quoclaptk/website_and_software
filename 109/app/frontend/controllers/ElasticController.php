<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\Subdomain;
use Modules\Models\Product;
use Modules\Models\News;
use Modules\Models\TmpProductCategory;
use Modules\Models\TmpNewsNewsMenu;
use Modules\Models\TmpProductProductElementDetail;

class ElasticController extends BaseController
{
    /**
     * index all subdomain action with each page
     * @return array
     */
    public function indexSubdomainAction()
    {
        $page = $this->request->getQuery('page') ? $this->request->getQuery('page') : 0;
        $limit = $this->request->getQuery('limit') ? $this->request->getQuery('limit') : 100;

        $offset = $page * $limit;

        $subdomains = Subdomain::find([
            "conditions" => "name != '@'",
            "order" => "special DESC, active DESC, id DESC",
            "limit" => $limit,
            "offset" => $offset
        ]);

        if (count($subdomains) > 0) {
            $this->elastic_service->_indexSubdomain($subdomains);
        }
        
        $this->view->disable();
    }

    /**
     * index all data product news in subdomain action with each page
     * @return array
     */
    public function indexDataAction()
    {
        $page = $this->request->getQuery('page') ? $this->request->getQuery('page') : 0;

        $offset = $page * 500;

        $newss = News::find([
            "column" => "id",
            "limit" => 500,
            "offset" => $offset,
            "order" => "id ASC"
        ]);

        if (count($newss) > 0) {
            $this->elastic_service->_indexTable($newss, 'news');
        }
        
        $this->view->disable();
    }

    /**
     * Index subdomain item
     *
     * @param int $id
     * @return array
     */
    public function indexSubdomainIdAction($id)
    {
        $this->elastic_service->insertSubdomain($id);
        $this->view->disable();
    }
}
