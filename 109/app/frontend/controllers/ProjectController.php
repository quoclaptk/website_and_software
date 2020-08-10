<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\Subdomain;
use Phalcon\Paginator\Adapter\QueryBuilder;
use Phalcon\Mvc\View;

class ProjectController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
        $this->_subdomain = "Modules\Models\Subdomain";
    }

    public function indexAction()
    {
        $titleBar = $this->_word['_du_an'];
        $breadcrumb = "<li class='active'>$titleBar</li>";

        // $subdomains = $this->subdomain_service->getSubdomainList();
        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url('du-an-da-thuc-hien') : $this->tag->site_url($langCode . '/' . $this->router->getControllerName());
            }
        }

        $this->view->languageUrls = $languageUrls;
        $this->view->breadcrumb = $breadcrumb;
        // $this->view->subdomains = $subdomains;
        $this->view->title_bar = $titleBar;
    }

    public function allSubdomainAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $page = $this->request->getPost('page');

            $subdomains = $this->modelsManager->createBuilder()
                ->columns("name")
                ->from($this->_subdomain)
                ->where("name != '@'")
                ->orderBy("$this->_subdomain.id DESC");

            $paginator = new QueryBuilder(
                [
                    "builder" => $subdomains,
                    "limit"   => 10,
                    "page"    => $page,
                ]
            );

            $this->view->page = $paginator->getPaginate();
            $this->view->url_page = 'all-subdomain';
            $this->view->pick($this->_getControllerName() . '/allSubdomain');
        } else {
            $this->view->disable();
        }
    }

    public function ajaxSubdomainAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $this->view->setRenderLevel(
                View::LEVEL_ACTION_VIEW
            );
            $page = $this->request->getPost('page');
            $result = $this->elastic_service->searchAllSubdomain($page);
            $this->view->result = $result;
            $this->view->url_page = 'ajax-subdomain';
            $this->view->pick($this->_getControllerName() . '/ajaxSubdomain');
        } else {
            $this->view->disable();
        }
    }

    public function searchAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $this->view->setRenderLevel(
                View::LEVEL_ACTION_VIEW
            );
            $keyword = $this->request->getPost('keyword');
            $result = $this->elastic_service->searchSubdomain($keyword, ['limit' => 2000]);

            $this->view->result = $result;
            $this->view->pick($this->_getControllerName() . '/search');
        } else {
            $this->view->disable();
        }
    }
}
