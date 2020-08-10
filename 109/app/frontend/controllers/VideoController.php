<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\CategoryVideo;
use Modules\Models\Video;
use Modules\Models\Clip;
use Modules\PhalconVn\General;
use Phalcon\Mvc\Model\Query;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class VideoController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
    }

    public function indexAction($page = 1)
    {
        $gerenal = new General();
        $title_bar = $this->_word['_video'];
        ;

        $breadcrumb = "<li class='active'>$title_bar</li>";

        $clip = Clip::find(
            array(
                "columns" => "id, name, slug, photo, folder, created_at",
                "conditions" => "subdomain_id = $this->_subdomain_id AND language_id = $this->languageId AND active = 'Y' AND deleted = 'N'",
                "order" => "sort ASC, id DESC"
            )
        );

        if (count($clip) > 0 && count($this->_tmpSubdomainLanguages) <= 1) {
            return $this->dispatcher->forward(['action' => 'detail', 'params' => [$clip[0]->slug]]);
        }

        $paginator   = new PaginatorModel(
            array(
                "data"  => $clip,
                "limit" => 24,
                "page"  => $page
            )
        );

        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url('video') : $this->tag->site_url($langCode . '/' . $this->router->getControllerName());
            }
        }

        $this->view->languageUrls = $languageUrls;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title_bar = $title_bar;
        $this->view->clip = $clip;
        $this->view->page = $paginator->getPaginate();
        $this->view->url_page = 'video';
    }

    public function layoutAction($layout = 1, $page = 1)
    {
        $this->view->setTemplateBefore('demo0'. $layout);
        $gerenal = new General();
        $title_bar = 'Video';

        $breadcrumb = "<li class='active'>Video</li>";

        $clip = Clip::find(
            array(
                "columns" => "id, name, slug, photo, folder, created_at",
                "conditions" => "subdomain_id = $this->_subdomain_id AND active = 'Y' AND deleted = 'N'",
                "order" => "sort ASC, id DESC"
            )
        );

        $paginator   = new PaginatorModel(
            array(
                "data"  => $clip,
                "limit" => 24,
                "page"  => $page
            )
        );


        $this->view->layout_router = $layout;
        $this->view->layout = $layout;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->title_bar = $title_bar;
        $this->view->clip = $clip;
        $this->view->page = $paginator->getPaginate();
        $this->view->url_page = 'video';
        $this->view->url_page = $layout . '/video';
        $this->view->pick($this->_getControllerName() . '/index');
    }

    public function categoryAction($slug, $page = 1)
    {
        $category = CategoryVideo::findFirst(
            array(
                "columns" => "id, name, slug, title, keywords, description",
                "conditions" => "slug = '".$slug."' AND active = 'Y'",
            )
        );

        if (empty($category)) {
            return $this->dispatcher->forward(['controller' => 'error', 'action' => 'show404']);
        }

        $title_bar = $category->name;

        $currentPage =  $page;

        $video = Video::find(
            array(
                "columns" => "id, name, slug, photo, folder",
                "conditions" => "category_video_id = $category->id AND active = 'Y'",
                "order" => "sort ASC, id DESC",
            )
        );

        $paginator   = new PaginatorModel(
            array(
                "data"  => $video,
                "limit" => 24,
                "page"  => $currentPage
            )
        );

        // $this->view->pick('video/index');
        $this->view->title = (!empty($category->title))? $category->title : $category->name;
        $this->view->keywords = (!empty($category->keywords))? $category->keywords : $category->name;
        $this->view->description = (!empty($category->description))? $category->description : $category->name;
        $this->view->category = $category;
        $this->view->title_bar = $title_bar;
        $this->view->page = $paginator->getPaginate();
        $this->view->url_page = 'video/cat/' . $slug . '';
    }

    public function detailAction($slug)
    {
        $detail = Clip::findFirst(
            array(
                "conditions" => "slug = '".$slug."' AND subdomain_id = $this->_subdomain_id AND language_id = $this->languageId AND active = 'Y'",
            )
        );

        $id = $detail->id;
        $hits = $detail->hits + 1;

        $breadcrumb = "<li><a href='". $this->tag->site_url('video') ."'>Video</a></li>";
        $breadcrumb .= "<li class='active'>$detail->name</li>";

        $phql   = "UPDATE Modules\Models\Clip SET hits = $hits WHERE id = $id";
        $result = $this->modelsManager->executeQuery($phql);
        if ($result->success() == false) {
            foreach ($result->getMessages() as $message) {
                echo $message->getMessage();
            }
        }

        $other_clip = Clip::find(
            array(
                "columns" => "id, name, slug, photo, folder",
                "conditions" => "subdomain_id = $this->_subdomain_id AND language_id =  $this->languageId AND id != $id AND active = 'Y' AND deleted = 'N'",
                "order" => "id DESC"
            )
        );

        $image_meta = _URL . '/' . _upload_youtube . $detail->folder . '/' . $detail->photo;
        $languageSlugs = [];
        if ($this->languageCode == 'vi') {
            $languageSlugs[$this->languageId] = $detail->slug;
            $dependLangs = Clip::findByDependId($detail->id);
            if (count($dependLangs) > 0) {
                foreach ($dependLangs as $dependLang) {
                    $languageSlugs[$dependLang->language_id] = $dependLang->slug;
                }
            }
        } else {
            $itemVi = Clip::findFirstById($detail->depend_id);
            $languageSlugs[$itemVi->language_id] = $itemVi->slug;
            $dependLangs = Clip::findByDependId($itemVi->id);
            if (count($dependLangs) > 0) {
                foreach ($dependLangs as $dependLang) {
                    $languageSlugs[$dependLang->language_id] = $dependLang->slug;
                }
            }
        }

        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langId = $tmpSubdomainLanguage->language_id;
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url($languageSlugs[$langId]) : $this->tag->site_url($langCode . '/' . $languageSlugs[$langId]);
            }
        }

        $this->view->languageUrls = $languageUrls;
        $this->view->title = (!empty($detail->title))? $detail->title : $detail->name;
        $this->view->keywords = (!empty($detail->keywords))? $detail->keywords : $detail->name;
        $this->view->description = (!empty($detail->description))? $detail->description : $detail->name;
        $this->view->image_meta = $image_meta;
        $this->view->breadcrumb  = $breadcrumb;
        $this->view->detail = $detail;
        $this->view->other_clip = $other_clip;
        $this->view->created_at = date('H:i:s d/m/Y', strtotime($detail->created_at));
    }

    public function detailLayoutAction($layout = 1, $slug)
    {
        $this->view->setTemplateBefore('demo0'. $layout);
        $detail = Clip::findFirst(
            array(
                "conditions" => "slug = '".$slug."' AND active = 'Y'",
            )
        );

        $id = $detail->id;
        $hits = $detail->hits + 1;

        $breadcrumb = "<li><a href='". $this->tag->site_url('video') ."'>Video</a></li>";
        $breadcrumb .= "<li class='active'>$detail->name</li>";

        $phql   = "UPDATE Modules\Models\Clip SET hits = $hits WHERE id = $id";
        $result = $this->modelsManager->executeQuery($phql);
        if ($result->success() == false) {
            foreach ($result->getMessages() as $message) {
                echo $message->getMessage();
            }
        }

        $other_clip = Clip::find(
            array(
                "columns" => "id, name, slug, photo, folder",
                "conditions" => "subdomain_id = $this->_subdomain_id AND id != $id AND active = 'Y' AND deleted = 'N'",
                "order" => "id DESC",
                "limit" => 24
            )
        );


        $image_meta = _URL . '/' . _upload_youtube . $detail->folder . '/' . $detail->photo;

        $this->view->layout_router = $layout;
        $this->view->layout = $layout;
        $this->view->title = (!empty($detail->title))? $detail->title : $detail->name;
        $this->view->keywords = (!empty($detail->keywords))? $detail->keywords : $detail->name;
        $this->view->description = (!empty($detail->description))? $detail->description : $detail->name;
        $this->view->image_meta = $image_meta;
        $this->view->breadcrumb  = $breadcrumb;
        $this->view->detail = $detail;
        $this->view->other_clip = $other_clip;
        $this->view->created_at = date('H:i:s d/m/Y', strtotime($detail->created_at));
        $this->view->url_page = $layout . '/' . $detail->slug;
        $this->view->pick($this->_getControllerName() . '/detail');
    }
}
