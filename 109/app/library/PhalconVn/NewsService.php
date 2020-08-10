<?php

namespace Modules\PhalconVn;

use Modules\Models\News;
use Modules\Models\NewsType;
use Modules\Models\NewsMenu;
use Modules\PhalconVn\NewsMenuService;

class NewsService extends BaseService
{
    /**
     *
     * @var cache key $cacheKey
     */
    protected $cacheKey;

    public function __construct()
    {
        parent::__construct();
        $this->cacheKey = 'news:' . $this->_subdomain_id;
        include_once('dom/simple_html_dom.php');
    }

    /**
     * Get news lastest
     *
     * @param int $limit
     * @return mixed
     */
    public function getNewsLastest($limit = 10)
    {
        $hasKey = __FUNCTION__ . '_' . $limit;
        $news = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($news === null) {
            $news = News::find([
                "columns" => "id, name, slogan, slug, summary, photo, folder, created_at",
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND language_id = $this->_lang_id AND active = 'Y' AND deleted = 'N'",
                "limit" => $limit,
                "order" => "sort ASC, id DESC"
            ]);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $news);
        }

        return $news;
    }

    /**
     * Get news type
     *
     * @param string $type
     * @param int $limit
     * @return mixed
     */
    public function getNewsHot($type = "hot", $limit = 10)
    {
        $hasKey = __FUNCTION__ . '_' . $type . '_' . $limit;
        $news = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($news === null) {
            $news = News::find([
                "columns" => "id, name, slogan, slug, summary, photo, folder, created_at",
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND language_id = $this->_lang_id AND $type = 'Y' AND active = 'Y' AND deleted = 'N'",
                "order" => "sort ASC, id DESC"
            ]);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $news);
        }

        return $news;
    }

    public function getNewsTypeInfoType($type)
    {
        return NewsType::findFirst([
            "columns" => "id, name, slug, type",
            "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND type = '$type' AND active = 'Y' AND deleted = 'N'"
        ]);
    }

    public function getlistNews($news_type, $limit = null)
    {
        return News::find([
            "columns" => "id, name, slug, summary, photo, folder, created_at",
            "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND type_id = $news_type AND active = 'Y' AND deleted = 'N'",
            "order" => "sort ASC, id DESC",
            "limit" => $limit
        ]);
    }

    /**
     * Get list news in news menu footer
     *
     * @return array $result
     */
    public function getNewsMenuFooter()
    {
        $hasKey = __FUNCTION__;
        $results = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array']);
        if ($results === null) {
            $newsMenus = NewsMenu::find([
                "columns" => "id, name, slug",
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND language_id = $this->_lang_id AND footer = 'Y' AND active = 'Y' AND deleted = 'N'"
            ]);

            $results = [];
            if (count($newsMenus) > 0) {
                $i = 0;
                foreach ($newsMenus as $key => $value) {
                    $listCategoryTreeId = $this->news_menu_service->getCategoryTreeId($value->id);
                    $listCategoryTreeId = (count($listCategoryTreeId) > 1) ? implode(",", $listCategoryTreeId) : $listCategoryTreeId[0];
                    $news = News::query()
                        ->columns(["id, name, slug"])
                        ->join("Modules\Models\TmpNewsNewsMenu", "tmp.news_id = Modules\Models\News.id", "tmp")
                        ->where("news_menu_id IN ($listCategoryTreeId)")
                        ->andWhere("language_id = :language_id:")
                        ->andWhere("active = :active:")
                        ->andWhere("deleted = :deleted:")
                        ->bind(["language_id" => $this->_lang_id,"active" => "Y", "deleted" => "N"])
                        ->orderBy("sort ASC, id DESC")
                        ->groupBy("id")
                        ->limit(5)
                        ->execute();

                    $results[$i] = $value->toArray();
                    if (count($news) > 0) {
                        $results[$i]['news'] = $news->toArray();
                    } else {
                        $results[$i]['news'] = [];
                    }
                    $i++;
                }
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $results);
        }
        
        return $results;
    }

    /**
     * Get news menu show home
     *
     * @param int $limit
     * @return int $results
     */
    public function getNewsMenuShowHome($limit = 0)
    {
        $hasKey = __FUNCTION__ . '_' . $limit;
        $results = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array']);
        if ($results === null) {
            $newsMenus = NewsMenu::find([
                "columns" => "id, name, slug",
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND language_id = $this->_lang_id AND home = 'Y' AND active = 'Y' AND deleted = 'N'",
                "order" => "sort ASC, id DESC"
            ]);

            $results = [];
            if (count($newsMenus) > 0) {
                $i = 0;
                foreach ($newsMenus as $value) {
                    $listCategoryTreeId = $this->news_menu_service->getCategoryTreeId($value->id);
                    $listCategoryTreeId = (count($listCategoryTreeId) > 1) ? implode(",", $listCategoryTreeId) : $listCategoryTreeId[0];
                    $news = News::query()
                        ->columns(["id, name, slug, summary, photo, folder, created_at"])
                        ->join("Modules\Models\TmpNewsNewsMenu", "tmp.news_id = Modules\Models\News.id", "tmp")
                        ->where("news_menu_id IN ($listCategoryTreeId)")
                        ->andWhere("active = :active:")
                        ->andWhere("deleted = :deleted:")
                        ->bind(["active" => "Y", "deleted" => "N"])
                        ->orderBy("sort ASC, id DESC")
                        ->groupBy("id")
                        ->limit($limit)
                        ->execute();

                   
                    // if (count($news) > 0) {
                    $results[] = $value->toArray();
                    $results[$i]['news'] = $news->toArray();
                    $i++;
                    // }
                }
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $results);
        }

        return $results;
    }

    /**
     * get other news detail
     *
     * @param int $newsId
     * @param string $listCategoryTreeId
     * @param int $limit default 20
     * @return mixed
     */
    public function getOtherNews($newsId, $listCategoryTreeId, $limit = 10)
    {
        $hasKey = __FUNCTION__ . '_' . $newsId;
        $newss = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($newss === null) {
            $newss = News::query()
                ->columns(["id, name, slug, photo, folder, summary, created_at"])
                ->join("Modules\Models\TmpNewsNewsMenu", "tmp.news_id = Modules\Models\News.id", "tmp")
                ->where("Modules\Models\News.subdomain_id = $this->_subdomain_id AND language_id = $this->_lang_id  AND news_menu_id IN ($listCategoryTreeId) AND id != $newsId AND active = 'Y' AND deleted = 'N'")
                ->orderBy("sort ASC, id DESC")
                ->groupBy("id")
                ->limit($limit)
                ->execute();

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $newss);
        }

        return $newss;
    }

    public function replaceImageErrorInArticleHome($html)
    {
        $hasKey = __FUNCTION__;
        $htmlReplace = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($htmlReplace === null) {
            $noImage = '/assets/images/no-image.jpg';
            $articles = str_get_html($html);
            $images = [];
            foreach ($articles->find('img') as $img) {
                array_push($images, $img->src);
            }
            
            $noImageReplaces = [];
            if (!empty($images)) {
                foreach ($images as $key => $image) {
                    $exist = $this->checkRemoteFile($image);
                    if ($exist) {
                        unset($images[$key]);
                    } else {
                        $noImageReplaces[] = $noImage;
                    }
                }
            }

            $images = array_values($images);
            $htmlReplace = str_replace($images, $noImageReplaces, $html);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $htmlReplace);
        }
        
        return $htmlReplace;
    }

    private function checkRemoteFile($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        // don't download content
        curl_setopt($ch, CURLOPT_NOBODY, 1);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if(curl_exec($ch) !== FALSE) {
            return true;
        }  else {
            return false;
        }
    }
}
