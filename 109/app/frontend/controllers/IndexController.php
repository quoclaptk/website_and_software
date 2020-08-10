<?php namespace Modules\Frontend\Controllers;

use Modules\Models\Setting;
use Modules\Models\TmpModuleGroupLayout;
use Modules\Models\TmpLayoutModule;
use Modules\Models\LayoutConfig;
use Modules\Models\Subdomain;
use Modules\PhalconVn\General;
use Phalcon\Mvc\Model\Query;
use Phalcon\Paginator\Factory;
use Phalcon\Paginator\Adapter\QueryBuilder;

class IndexController extends BaseController
{
    private $_subdomain_id;

    public function onConstruct()
    {
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
        $this->_news = "Modules\Models\News";
        $this->_product = "Modules\Models\Product";
        $this->_subdomain = "Modules\Models\Subdomain";
    }

    public function indexAction($page = 1)
    {
        /*if ($this->_subdomain_id == 1) {
            $this->view->setTemplateBefore('demo02');
            $news = $this->modelsManager->createBuilder()
                ->columns("$this->_news.id, $this->_news.name, $this->_news.slug, $this->_news.photo, $this->_news.folder, s.name as subdomain_name, s.folder as subdomain_folder")
                ->where("$this->_news.language_id = 1")
                ->from($this->_news)
                ->join("Modules\Models\Subdomain", "s.id = $this->_news.subdomain_id", "s")
                ->orderBy("$this->_news.id DESC");

            $paginatorNews = new QueryBuilder(
                [
                    "builder" => $news,
                    "limit"   => 10,
                    "page"    => $page,
                ]
            );

            $products = $this->modelsManager->createBuilder()
                ->columns("$this->_product.id, $this->_product.name, $this->_product.slug, $this->_product.photo, $this->_product.folder, s.name as subdomain_name, s.folder as subdomain_folder")
                ->where("$this->_product.language_id = 1")
                ->from($this->_product)
                ->join("Modules\Models\Subdomain", "s.id = $this->_product.subdomain_id", "s")
                ->orderBy("$this->_product.id DESC");

            $paginatorProduct = new QueryBuilder(
                [
                    "builder" => $products,
                    "limit"   => 10,
                    "page"    => $page,
                ]
            );

            $subdomains = $this->modelsManager->createBuilder()
                ->columns("name")
                ->from($this->_subdomain)
                ->where("name != '@'")
                ->orderBy("$this->_subdomain.id DESC");

            $paginatorSubdomain = new QueryBuilder(
                [
                    "builder" => $subdomains,
                    "limit"   => 10,
                    "page"    => $page,
                ]
            );

            $title_bar_news = "Tổng hợp tin tức";
            $title_bar_product = "Tổng hợp sản phẩm";
            $title_bar_subdomain = "Tổng hợp tên miền";
            $this->view->pageNews = $paginatorNews->getPaginate();
            $this->view->pageProduct = $paginatorProduct->getPaginate();
            $this->view->pageSubdomain = $paginatorSubdomain->getPaginate();
            $this->view->title_bar_news = $title_bar_news;
            $this->view->title_bar_product = $title_bar_product;
            $this->view->title_bar_subdomain = $title_bar_subdomain;

            $this->view->url_page = 'trang-chu';
        }*/
    }


    /*public function demo01Action()
    {
        $layoutConfig = LayoutConfig::findFirst([
            "conditions" => "subdomain_id = ". $subdomain->id ." AND layout_id = 1"
        ]);
        $cacheKey = $this->_subdomain_id . '-tmpLayoutModule-' . $layoutConfig->layout_id;
        $tmpLayoutModule = $this->cache_service->get($cacheKey);
        if ($tmpLayoutModule === null) {
            $tmpLayoutModule = $this->getTmpLayoutModule(1);
            $this->cache_service->save($cacheKey, $tmpLayoutModule);
        }
        
        $this->view->tmpLayoutModule = $tmpLayoutModule;
        $this->view->layout = 1;
        $this->view->layout_config = $layoutConfig;
        $this->view->layout_router = 1;
        $this->view->setTemplateBefore('demo01');
    }

    public function demo02Action()
    {
        $subdomain = $this->mainGlobal->checkDomain();
        $layoutConfig = LayoutConfig::findFirst([
            "conditions" => "subdomain_id = ". $subdomain->id ." AND layout_id = 2"
        ]);
        if ($layoutConfig->hide_left == 'Y' && $layoutConfig->hide_right == 'Y') {
            $layout = 'demo01';
        } elseif ($layoutConfig->hide_left == 'Y' && $layoutConfig->hide_right == 'N') {
            $layout = 'demo04';
        } elseif ($layoutConfig->hide_left == 'N' && $layoutConfig->hide_right == 'Y') {
            $layout = 'demo03';
        } else {
            $layout = 'demo02';
        }
        $cacheKey = $this->_subdomain_id . '-tmpLayoutModule-' . $layoutConfig->layout_id;
        $tmpLayoutModule = $this->cache_service->get($cacheKey);
        if ($tmpLayoutModule === null) {
            $tmpLayoutModule = $this->getTmpLayoutModule(2);
            $this->cache_service->save($cacheKey, $tmpLayoutModule);
        }
        
        $this->view->tmpLayoutModule = $tmpLayoutModule;
        $this->view->layout = 2;
        $this->view->layout_config = $layoutConfig;
        $this->view->layout_router = 2;
        $this->view->setTemplateBefore($layout);
    }

    public function demo03Action()
    {
        $layoutConfig = LayoutConfig::findFirst([
            "conditions" => "subdomain_id = ". $subdomain->id ." AND layout_id = 3"
        ]);
        $cacheKey = $this->_subdomain_id . '-tmpLayoutModule-' . $layoutConfig->layout_id;
        $tmpLayoutModule = $this->cache_service->get($cacheKey);
        if ($tmpLayoutModule === null) {
            $tmpLayoutModule = $this->getTmpLayoutModule(3);
            $this->cache_service->save($cacheKey, $tmpLayoutModule);
        }
        $this->view->tmpLayoutModule = $tmpLayoutModule;
        $this->view->layout = 3;
        $this->view->layout_config = $layoutConfig;
        $this->view->layout_router = 3;
        $this->view->setTemplateBefore('demo03');
    }

    public function demo04Action()
    {
        $layoutConfig = LayoutConfig::findFirst([
            "conditions" => "subdomain_id = ". $subdomain->id ." AND layout_id = 4"
        ]);
        $cacheKey = $this->_subdomain_id . '-tmpLayoutModule-' . $layoutConfig->layout_id;
        $tmpLayoutModule = $this->cache_service->get($cacheKey);
        if ($tmpLayoutModule === null) {
            $tmpLayoutModule = $this->getTmpLayoutModule(4);
            $this->cache_service->save($cacheKey, $tmpLayoutModule);
        }
        $this->view->tmpLayoutModule = $tmpLayoutModule;
        $this->view->layout = 4;
        $this->view->layout_config = $layoutConfig;
        $this->view->layout_router = 4;
        $this->view->setTemplateBefore('demo04');
    }

    public function subdomainAction($subdomainName)
    {
        $subdomain = Subdomain::findFirstByName($subdomainName);
        if ($subdomain) {
            $id = $subdomain->id;

            $layoutInfo = $this->mainGlobal->getLayoutTemplate($subdomain);
            $layout = $layoutInfo['layout'];
            $layout_id = $layoutInfo['layout_id'];

            $cacheKey = $this->_subdomain_id . '-tmpLayoutModuleDemo-' . $id;
            $tmpLayoutModule = $this->cache_service->get($cacheKey);
            if ($tmpLayoutModule === null) {
                $tmpLayoutModule = $this->getTmpLayoutModuleDemo($id, $layout_id);
                $this->cache_service->save($cacheKey, $tmpLayoutModule);
            }
            $this->view->tmpLayoutModule = $tmpLayoutModule;
            $this->view->layout = $layout_id;

            $this->view->layout_router = $layout_id;
            $this->view->demo_router = $subdomainName;
            $this->view->demo_folder = $subdomain->folder;
            $this->view->setTemplateBefore($layout);
        }
    }*/

    public function adminLoginAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $subdomain = $this->mainGlobal->checkDomain();

            if ($this->request->getPost('ok_left') != '') {
                $this->auth->check(array(
                    'subdomain_id' => $subdomain->id,
                    'username' => $this->request->getPost('adm_username_left'),
                    'password' => $this->request->getPost('adm_password_left')
                ));
            }

            if ($this->request->getPost('ok_right') != '') {
                $this->auth->check(array(
                    'subdomain_id' => $subdomain->id,
                    'username' => $this->request->getPost('adm_username_right'),
                    'password' => $this->request->getPost('adm_password_right')
                ));
            }

            if ($this->request->getPost('ok_center') != '') {
                $this->auth->check(array(
                    'subdomain_id' => $subdomain->id,
                    'username' => $this->request->getPost('adm_username_center'),
                    'password' => $this->request->getPost('adm_password_center')
                ));
            }

            if (!empty($this->session->get('auth-identity'))) {
                echo 1;
            } else {
                echo 0;
            }
        }

        $this->view->disable();
    }

    public function deleteAllCacheAction()
    {
        $this->view->disable();
    }

    public function notfoundAction()
    {
        $this->response->redirect("/");
    }
}
