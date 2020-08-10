<?php namespace Modules\Frontend\Controllers;

use Modules\Models\NewsType;
use Modules\Models\Category;
use Modules\Models\NewsCategory;
use Modules\Models\NewsMenu;
use Modules\Models\TmpNewsNewsMenu;
use Modules\Models\News;
use Modules\Models\Tags;
use Modules\Models\Subdomain;
use Phalcon\Mvc\Model\Query;
use Phalcon\Paginator\Adapter\QueryBuilder;

use Phalcon\Paginator\Adapter\Model as PaginatorModel;

class NewsController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
        $this->_news = "Modules\Models\News";
        $this->_numper_item = $this->_config['_cf_text_number_news_on_page'];
    }

    public function indexAction()
    {
        $title_bar = 'Tin tức';

        $category = Category::find(array(
            "conditions" => "active = 'Y'",
            "order" => "sort ASC, id DESC",
        ))->toArray();



        for ($i=0; $i<count($category); $i++) {
            $category[$i]['news'] = News::find(
                array(
                    "columns" => "id, name, slug, folder, photo, summary",
                    "conditions" => "active = 'Y' AND category_id = ".$category[$i]['id']."",
                    "order" => "sort ASC, id DESC",
                    "limit" => 5,
                )
            )->toArray();
        }

        $this->view->title_bar = $title_bar;
        $this->view->category = $category;


        //$this->view->disable();
    }

    public function allNewsAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $this->view->setTemplateBefore('allNews');
            $page = $this->request->getPost('page');

            $news = $this->modelsManager->createBuilder()
                ->columns("$this->_news.id, $this->_news.name, $this->_news.slug, $this->_news.photo, $this->_news.folder, s.name as subdomain_name, s.folder as subdomain_folder")
                ->where("$this->_news.language_id = 1")
                ->from($this->_news)
                ->join("Modules\Models\Subdomain", "s.id = $this->_news.subdomain_id", "s")
                ->orderBy("$this->_news.id DESC");
                
            $paginator =$this->pagination_service->queryBuilder($news, 10, $page);

            $this->view->page = $paginator;
            $this->view->url_page = 'all-news';
            $this->view->pick($this->_getControllerName() . '/allNews');
        }
    }

    public function typeAction($slug, $page = 1)
    {
        $type = NewsType::findFirst([
            "columns" => "id, name, slug, content, title, keywords, description, created_at",
            "conditions" => "subdomain_id = $this->_subdomain_id AND slug = '$slug' AND active = 'Y' AND deleted = 'N'"
        ]);
        $id = $type->id;
        $name = $type->name;
        $url = $type->slug;

        $news = News::find([
            "columns" => "id, name, slug, photo, folder, summary, created_at",
            "conditions" => "type_id = $id AND subdomain_id = $this->_subdomain_id AND active = 'Y' AND deleted = 'N'",
            "order" => "sort ASC, id DESC",
        ]);

        $breadcrumb = "<li class='active'>$name</li>";

        $title_bar = $name;

        $paginator   = new PaginatorModel(
            array(
                "data"  => $news,
                "limit" => $this->_numper_item,
                "page"  => $page
            )
        );

        $this->view->title = $name;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->type = $type;
        $this->view->news = $news;
        $this->view->title_bar = $title_bar;
        $this->view->page = $paginator->getPaginate();
        $this->view->url_page = $url;
    }

    public function categoryAction($slug, $page = 1)
    {
        $category = NewsCategory::findFirst([
            "conditions" => "subdomain_id = $this->_subdomain_id AND slug = '$slug' AND active = 'Y' AND deleted = 'N'"
        ]);
        

        $type = NewsType::findFirstById($category->type_id);
        

        $breadcrumb = "";
        $breadcrumb .= "<li><a href='". $this->tag->site_url($type->slug) ."'>". $type->name ."</a></li>";
        if ($category->level > 0) {
            $nested = $this->news_category->nestedCategory($category->parent_id);

            if (isset($nested['parent'])) {
                $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['parent']['slug']) .'">'. $nested['parent']['name'] .'</a></li>';
            }
            $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['slug']) .'">'. $nested['name'] .'</a></li>';
        }
        $breadcrumb .= "<li class='active'>$category->name</li>";

        $listCategoryTreeId = $this->news_category->getCategoryTreeId($category->id);
        $listCategoryTreeId = (count($listCategoryTreeId) > 1) ? implode(",", $listCategoryTreeId) : $listCategoryTreeId[0];

        //get Data
        $news = $this->modelsManager->createBuilder()
            ->from(MODELS . "\News")
            ->columns("id, name, slug, photo, folder, summary, created_at")
            ->join("Modules\Models\TmpNewsNewsCategory", "tmp.news_id = Modules\Models\News.id", "tmp")
            ->where("news_category_id IN ($listCategoryTreeId)")
            ->andWhere("active = 'Y' AND deleted = 'N'")
            ->orderBy("sort ASC, id DESC")
            ->groupBy("id");

        $paginator = $this->pagination_service->queryBuilder($news, $this->_numper_item, $page);

        $title_bar = $category->name;
        $title = (!empty($category->title)) ? $category->title : $category->name;

        $this->view->title = $title;
        $this->view->keywords = $category->keywords;
        $this->view->description = $category->description;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->category = $category;
        $this->view->news = $news;
        $this->view->type = $type;
        $this->view->title_bar = $title_bar;
        $this->view->page = $paginator;
        $this->view->url_page = $category->slug;
        // $this->view->pick($this->_getControllerName() . '/type');
    }

    /**
     * Display news category
     * 
     * @param $slug
     * @param $page
     * @return View
     */
    public function menuAction($slug, $page = 1)
    {
        $category = NewsMenu::findFirst([
            "conditions" => "subdomain_id = $this->_subdomain_id AND language_id = $this->languageId AND slug = '$slug' AND active = 'Y' AND deleted = 'N'"
        ]);

        if (!$category) {
            return $this->dispatcher->forward(['controller' => 'index', 'action' => 'notfound']);
        }

        $breadcrumb = "";
        if ($category->level > 0) {
            $nested = $this->news_menu_service->nestedCategory($category->parent_id);

            if (isset($nested['parent'])) {
                $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['parent']['slug']) .'">'. $nested['parent']['name'] .'</a></li>';
            }
            $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['slug']) .'">'. $nested['name'] .'</a></li>';
        }
        $breadcrumb .= "<li class='active'>$category->name</li>";

    
        //get Data
        $listCategoryTreeId = $this->news_menu_service->getCategoryTreeId($category->id);
        $listCategoryTreeId = (count($listCategoryTreeId) > 1) ? implode(",", $listCategoryTreeId) : $listCategoryTreeId[0];
        $news =  $this->modelsManager->createBuilder()
            ->from(MODELS . "\News")
            ->columns("id, name, slug, photo, folder, summary, created_at")
            ->join("Modules\Models\TmpNewsNewsMenu", "tmp.news_id = Modules\Models\News.id", "tmp")
            ->where("Modules\Models\News.subdomain_id = $this->_subdomain_id AND news_menu_id IN ($listCategoryTreeId) AND active = 'Y' AND deleted = 'N'")
            ->orderBy("sort ASC, id DESC")
            ->groupBy("id");

        $paginator = $this->pagination_service->queryBuilder($news, $this->_numper_item, $page);


        if ($paginator->total_items == 1) {
            return $this->dispatcher->forward(['controller' => 'news', 'action' => 'detail', 'params' => [$paginator->items[0]->slug]]);
        }

        // tmp module type
        $tmpTypeModules = $this->module_item_service->getTmpTypeModules();

        $title_bar = $category->name;
        $title = (!empty($category->title)) ? $category->title : $category->name;

        $languageSlugs = [];
        if ($this->languageCode == 'vi') {
            $languageSlugs[$this->languageId] = $category->slug;
            $dependLangs = NewsMenu::findByDependId($category->id);
            if (count($dependLangs) > 0) {
                foreach ($dependLangs as $dependLang) {
                    $languageSlugs[$dependLang->language_id] = $dependLang->slug;
                }
            }
        } else {
            $itemVi = NewsMenu::findFirstById($category->depend_id);
            $languageSlugs[$itemVi->language_id] = $itemVi->slug;
            $dependLangs = NewsMenu::findByDependId($itemVi->id);
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

        // set active menu
        $activeMenu = [
            'type' => 'news_menu',
            'id' => $category->id
        ];

        $this->view->category = $category;
        $this->view->languageUrls = $languageUrls;
        $this->view->title = $title;
        $this->view->keywords = $category->keywords;
        $this->view->description = $category->description;
        $this->view->showPopup = $category->popup;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->activeMenu = $activeMenu;
        $this->view->news = $news;
        $this->view->tmpTypeModules = $tmpTypeModules;
        $this->view->title_bar = $title_bar;
        $this->view->page = $paginator;
        $this->view->url_page = $category->slug;
        $this->view->pick($this->_getControllerName() . '/category');
    }
    
    public function tagsAction($tag_id, $page = 1)
    {
        $tag = \Modules\Models\Tags::findFirst(array(
            "columns" => "id, name, slug, title, keywords, description",
            "conditions" => "id = '".$tag_id."' AND active = 'Y'",
        ));
        
        if (empty($tag)) {
            return $this->dispatcher->forward(['controller' => 'error', 'action' => 'show404']);
        }
        
        $title_bar = 'Tag: ' . $tag->name;
        
        $currentPage =  $page;
        
        $news = $this->modelsManager->createBuilder()
                ->columns('news.id, news.name, news.slug, news.photo, news.folder, news.summary')
                ->addFrom('Modules\Models\News', 'news')
                ->leftJoin('Modules\Models\TmpNewsTags', 'tmp_news_tags.news_id = news.id', 'tmp_news_tags')
                ->where('tmp_news_tags.tag_id = '. $tag_id .'')
                ->orderBy('news.id DESC')
                ->getQuery()
                ->execute();
        
        $paginator   = new PaginatorModel(
             array(
                "data"  => $news,
                "limit" => 10,
                "page"  => $currentPage
            )
        );
        
        $this->view->title = (!empty($tag->title))? $tag->title : $tag->name;
        $this->view->keywords = (!empty($tag->keywords))? $tag->keywords : $tag->name;
        $this->view->description = (!empty($tag->description))? $tag->description : $tag->name;
        $this->view->tag_detail = $tag;
        $this->view->title_bar = $title_bar;
        $this->view->page = $paginator->getPaginate();
        $this->view->url_page = 'tags/' . $tag->slug . '-' . $tag_id . '/';
        
        $this->view->pick('news/category');
    }

    /**
     * Display news detail
     * @param string $slug
     * @return resource The page to redirect to
     */
    public function detailAction($slug)
    {
        // get item data
        $detail = $this->elastic_service->searchWithSlug($slug, 'news');
        
        $id = $detail->id;
        $type_id = $detail->type_id;
        $hits = $detail->hits + 1;

        $detail->hits = $hits;
        $detail->save();
        // $this->elastic_service->updateNews($id);

        $breadcrumb = "";

        if ($type_id != 0) {
            $newsType = NewsType::findFirst([
                "columns" => "id, name, slug",
                "conditions" => "id = $type_id"
            ]);
            
            $otherNews = News::find(
                array(
                    "columns" => "id, name, slug",
                    "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND type_id = $type_id AND id != $id AND active = 'Y'",
                    "order" => "sort ASC, id DESC",
                    "limit" => 10
                )
            );
            
            $breadcrumb .= "<li><a href='". $this->tag->site_url($newsType->slug) ."'>$newsType->name</a></li>";
        } else {
            $tmpNewsMenu = TmpNewsNewsMenu::findFirstByNewsId($id);
            if ($tmpNewsMenu) {
                $newsMenu = NewsMenu::findFirst([
                    "columns" => "id, name, slug",
                    "conditions" => "id = ". $tmpNewsMenu->news_menu_id .""
                ]);

                $listCategoryTreeId = $this->news_menu_service->getCategoryTreeId($newsMenu->id);
                $listCategoryTreeId = (count($listCategoryTreeId) > 1) ? implode(",", $listCategoryTreeId) : $listCategoryTreeId[0];

                $otherNews = $this->news_service->getOtherNews($id, $listCategoryTreeId);
                
                $categoryInfos = $this->news_menu_service->getMenuNewsDetail($detail->id);

                if (count($categoryInfos) > 0) {
                    /*foreach ($categoryInfos as $key => $categoryInfo) {
                        $nested = $this->category_service->nestedCategory($categoryInfo->id);
                        if (empty($nested)) {
                            continue;
                        }
                    }*/

                    $nested = $this->news_menu_service->nestedCategory($categoryInfos[0]->id);

                    if (!empty($nested['parent']['parent'])) {
                        $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['parent']['parent']['slug']) .'">'. $nested['parent']['parent']['name'] .'</a></li>';
                    }

                    if (!empty($nested['parent'])) {
                        $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['parent']['slug']) .'">'. $nested['parent']['name'] .'</a></li>';
                    }

                    if (!empty($nested)) {
                        $breadcrumb .= '<li><a href="'. $this->tag->site_url($nested['slug']) .'">'. $nested['name'] .'</a></li>';
                    }
                }
            }
        }

        $image_meta = PROTOCOL . HOST . '/' . _upload_news . $this->mainGlobal->getDomainFolder() . '/' . $detail->folder . '/' . $detail->photo;

        $languageSlugs = [];
        if ($this->languageCode == 'vi') {
            $languageSlugs[$this->languageId] = $detail->slug;
            $dependLangs = News::findByDependId($detail->id);
            if (count($dependLangs) > 0) {
                foreach ($dependLangs as $dependLang) {
                    $languageSlugs[$dependLang->language_id] = $dependLang->slug;
                }
            }
        } else {
            $itemVi = News::findFirstById($detail->depend_id);
            $languageSlugs[$itemVi->language_id] = $itemVi->slug;
            $dependLangs = News::findByDependId($itemVi->id);
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

        $headContent = $detail->head_content;
        $bodyContent = $detail->body_content;

        $this->view->headContent = $headContent;
        $this->view->bodyContent = $bodyContent;
        $this->view->languageUrls = $languageUrls;
        $this->view->title = (!empty($detail->title))? $detail->title : $detail->name;
        $this->view->keywords = (!empty($detail->keywords))? $detail->keywords : $detail->name;
        $this->view->description = (!empty($detail->description))? $detail->description : $detail->name;
        $this->view->image_meta = $image_meta;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->detail = $detail;
        $this->view->other_news = $otherNews;
        $this->view->created_at = date('H:i:s d/m/Y', strtotime($detail->created_at));
    }
}
