<?php
namespace Modules\PhalconVn;

use Modules\Models\NewsMenu;

class NewsMenuService extends BaseService
{
    /**
     *
     * @var cache key $cacheKey
     */
    protected $cacheKey;

    public function __construct()
    {
        parent::__construct();
        $this->_folder = $this->mainGlobal->getDomainFolder();
        $this->cacheKey = 'news:' . $this->_subdomain_id;
    }
    
    /**
     * nested list news menu
     * @param  integer $parentId
     * @return array           
     */
    public function nestedCategory($parentId = 0)
    {
        $hasKey = __FUNCTION__ . '_' . $parentId;
        $trees = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array']);
        if ($trees === null) {
            $subdomain_id = $this->_subdomain_id;
            $category = NewsMenu::findFirst([
                "columns" => "id, name, slug, parent_id, level",
                "order" => "sort ASC, id DESC",
                "conditions" => "id = ". $parentId ." AND subdomain_id = $subdomain_id AND language_id = $this->_lang_id AND active='Y' AND deleted = 'N'"
            ]);

            $trees = [];

            if ($category) {
                if ($category->parent_id != 0) {
                    $categoryParent = NewsMenu::findFirst([
                        'columns' => 'id',
                        'conditions' => 'id = '. $category->parent_id .' AND active = "Y"'
                    ]);

                    if (!$categoryParent) {
                        return $trees;
                    }
                }
                
                $category = $category->toArray();
                $trees = array(
                    "id" => $category["id"],
                    "parent_id" => $category["parent_id"],
                    "name" => $category["name"],
                    "slug" => $category["slug"],
                    "level" => $category["level"]
                );

                if ($category["level"] > 0) {
                    $trees["parent"] = $this->nestedCategory($category["parent_id"]);
                }
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $trees);
        }

        return $trees;
    }

    public function getCategoryTreeIdOld($parentId = 0, $trees = array())
    {
        $subdomain_id = $this->_subdomain_id;
        $trees[] = $parentId;

        $categories = NewsMenu::find(
            [
                "columns" => "id",
                "order" => "sort ASC, id DESC",
                "conditions" => "parent_id = ". $parentId ." AND subdomain_id = $subdomain_id AND active='Y' AND deleted = 'N'"
            ]
        );

        $trees_obj = array();
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $trees[] = $category->id;
                $trees   = $this->getCategoryTreeIdOld($category->id, $trees);
            }
        }
        if (!empty($trees)) {
            foreach ($trees as $tree) {
                $trees_obj[] = $tree;
            }
        }

        $trees_obj = array_unique($trees_obj);
        return $trees_obj;
    }

    /**
     * get list News Menu id
     * @param  integer $parentId
     * @param  array   $trees   
     * @param  null|array  $options 
     * @return array           
     */
    public function getCategoryTreeId($parentId = 0, $trees = array(), $options = null)
    {
        $subdomain_id = $this->_subdomain_id;
        $active = (isset($options['notActive']) && $options['notActive'] == true) ? '' : ' AND active = "Y"';
        if ($parentId != 0) {
            $trees[] = $parentId;
        }
        $categories = NewsMenu::find(["columns" => "id", "conditions" => "parent_id = " . $parentId . $active ." AND deleted = 'N' AND language_id = $this->_lang_id"]);
        if (count($categories) > 0) {
            foreach ($categories as $category) {
                $trees[] = $category->id;
                $trees = $this->getCategoryTreeId($category->id, $trees, $options);
            }
        }

        $trees = array_unique($trees);
        $trees = array_values($trees);

        return $trees;
    }

    /**
     * Get category news tree news detail
     *
     * @param int $newsId
     * @return mixed
     */
    public function getCategoryNewsDetail($newsId)
    {
        $hasKey = __FUNCTION__ . '_' . $newsId;
        $newsMenus = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($newsMenus === null) {
            $newsMenus = NewsMenu::query()
                ->columns(["id", "name", "slug", "parent_id"])
                ->join("Modules\Models\TmpNewsNewCategory", "tmp.category_id = Modules\Models\NewsMenu.id", "tmp")
                ->where("news_id = :news_id:")
                ->andWhere("active = :active:")
                ->andWhere("deleted = :deleted:")
                ->bind(["news_id" => $newsId, "active" => "Y", "deleted" => "N"])
                ->orderBy("level DESC, id DESC")
                ->execute();

                $this->_setHasKeyValue($this->cacheKey, $hasKey, $newsMenus);
            }

        return $newsMenus;
    }

    /**
     * Get category news tree news detail
     *
     * @param int $newsId
     * @return mixed
     */
    public function getMenuNewsDetail($newsId)
    {
        $hasKey = __FUNCTION__ . '_' . $newsId;
        $newsMenus = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($newsMenus === null) {
            $newsMenus = NewsMenu::query()
                ->columns(["id", "name", "slug", "parent_id"])
                ->join("Modules\Models\TmpNewsNewsMenu", "tmp.news_menu_id = Modules\Models\NewsMenu.id", "tmp")
                ->where("news_id = :news_id:")
                ->andWhere("active = :active:")
                ->andWhere("deleted = :deleted:")
                ->bind(["news_id" => $newsId, "active" => "Y", "deleted" => "N"])
                ->orderBy("level DESC, id DESC")
                ->execute();

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $newsMenus);
        }

        return $newsMenus;
    }

    /**
     * Get multi level news menu
     * @param $parentId
     * @param $level
     * @param $options
     * @return string
     */
    public function recursive($parentId = 0, $level = 0, $options = null)
    {
        $hasKey = __FUNCTION__ . '_' . $parentId . '_' . $level;
        if (!empty($options)) {
            foreach ($options as $key => $option) {
                $hasKey .= '_' . $key;
            }
        }

        $html = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($html === null) {
            $tag = new Tag();
            $categories = NewsMenu::find(["columns" => "id, name, slug, level, parent_id", "order" => "sort ASC, id DESC", "conditions" => "parent_id = " . $parentId . " AND subdomain_id = " . $this->_subdomain_id . " AND language_id = $this->_lang_id AND active = 'Y' AND deleted = 'N'"]);
            $levelZeroClass = '';
            $levelOneClass = '';
            $aClass = '';
            if (isset($options['level0'])) {
                $levelZeroClass = ' class="level0 horizental"';
                $aClass = ' class="level1"';
            }

            if (isset($options['level1'])) {
                $levelOneClass = ' level1';
            }

            if (isset($options['level2'])) {
                $levelOneClass = ' level2';
            }

            $html = null;
            if (count($categories) > 0) {
                $html .= '<ul'. $levelZeroClass .'>';
                foreach ($categories as $category) {
                    if (isset($options['router'])) {
                        $link = ($this->_lang_code == 'vi') ? $tag->site_url($options['router'] . '/' . $category->slug) : $tag->site_url($options['router'] . '/' . $this->_lang_code . '/' . $category->slug);
                    } else {
                        $link = ($this->_lang_code == 'vi') ? $tag->site_url($category->slug) : $tag->site_url($this->_lang_code . '/' . $category->slug);
                    }

                    if ($category->parent_id == $parentId) {
                        switch ($level) {
                            case '0':
                                $icon = 'caret-right';
                                break;

                            case '1':
                                $icon = 'angle-double-right ';
                                break;

                            case '2':
                                $icon = 'angle-right';
                                break;
                            
                            default:
                                $icon = 'caret-right';
                                break;
                        }
                        $optionChild = [];
                        $html.= '<li class="main_category_' . $level . $levelOneClass .'"><a href="' . $link . '"'. $aClass .'>';
                        if (!isset($options['level0']) && !isset($options['level1']) && !isset($options['level2'])) {
                            $html.= '<i class="fa fa-'. $icon .'" aria-hidden="true"></i>';
                        }

                        if (isset($options['level0'])) {
                            $optionChild = ['level1' => true];
                        }

                        if (isset($options['level1'])) {
                            $optionChild = ['level2' => true];
                        }

                        $html.= '<span>' . $category->name . '</span></a>';

                        $html.= $this->recursive($category->id, $level + 1, $optionChild);
                        $html.= '</li>';
                    }
                }

                $html .= '</ul>';
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $html);
        }

        return $html;
    }

    /**
     * Get multi level category vertical html
     * @param $parentId
     * @param $level
     * @param $options
     * @return string
     */
    public function recursiveVerticalMenu($parentId = 0, $options = null)
    {
        $hasKey = __FUNCTION__ . '_' . $parentId;
        if (!empty($options)) {
            foreach ($options as $key => $option) {
                $hasKey .= '_' . $key;
            }
        }

        $html = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($html === null) {
            $tag = new Tag();
            $categories = NewsMenu::find(["columns" => "id, name, slug, level, parent_id, icon_type, icon, font_class", "order" => "sort ASC, id DESC", "conditions" => "parent_id = " . $parentId . " AND subdomain_id = " . $this->_subdomain_id . " AND language_id = $this->_lang_id AND active='Y' AND deleted = 'N'"]);
            $html = null;
            if (count($categories) > 0) {
                $i = 0;
                $html .= '<ul style="display:block">';
                foreach ($categories as $category) {
                    $level = $category->level;
                    $iconType = $category->icon_type;
                    switch ($level) {
                        case 0:
                            if ($iconType == 2 && !empty($category->icon) && file_exists('files/icon/' . $this->_folder . '/' . $category->icon)) {
                                $icon = '<img src="/files/icon/'. $this->_folder . '/' . $category->icon .'" alt="'. $category->name .'">';
                            } else {
                                if ($iconType == 1 && !empty($category->font_class)) {
                                    $icon = '<i class="fa fa-'. $category->font_class .'"></i>';
                                } else {
                                    $icon = '<i class="fa fa-caret-right"></i>';
                                }
                            }

                            break;

                        case 1:
                            if ($iconType == 2 && !empty($category->icon) && file_exists('files/icon/' . $this->_folder . '/' . $category->icon)) {
                                $icon = '<img src="/files/icon/'. $this->_folder . '/' . $category->icon .'" alt="'. $category->name .'">';
                            } else {
                                if ($iconType == 1 && !empty($category->font_class)) {
                                    $icon = '<i class="fa fa-'. $category->font_class .'"></i>';
                                } else {
                                    $icon = '<i class="fa fa-angle-right"></i>';
                                }
                            }
                            
                            break;

                        case 2:
                            if ($iconType == 2 && !empty($category->icon) && file_exists('files/icon/' . $this->_folder . '/' . $category->icon)) {
                                $icon = '<img src="/files/icon/'. $this->_folder . '/' . $category->icon .'" alt="'. $category->name .'">';
                            } else {
                                if ($iconType == 1 && !empty($category->font_class)) {
                                    $icon = '<i class="fa fa-'. $category->font_class .'"></i>';
                                } else {
                                    $icon = '<i class="fa fa-circle"></i>';
                                }
                            }
                            
                            break;
                        
                        default:
                            $icon = '<i class="fa fa-caret-right"></i>';
                            break;
                    }
                    $plus = 'minus';
                    if (isset($options['router'])) {
                        $link = ($this->_lang_code == 'vi') ? $tag->site_url($options['router'] . '/' . $category->slug) : $tag->site_url($options['router'] . '/' . $this->_lang_code . '/' . $category->slug);
                    } else {
                        $link = ($this->_lang_code == 'vi') ? $tag->site_url($category->slug) : $tag->site_url($this->_lang_code . '/' . $category->slug);
                    }
                    
                    if ($category->parent_id == $parentId) {
                        $html.= '<li><a href="' . $link . '" class="active">'. $icon .'<span>' . $category->name . '</span></a>';
                      
                        $child = $this->recursiveVerticalMenu($category->id, $options);
                        if ($child != '') {
                            $html .= '<span class="subDropdown '. $plus .'"></span>';
                        }
                    
                        $html.= $this->recursiveVerticalMenu($category->id, $options);
                        $html.= '</li>';
                    }
                    $i++;
                }

                $html .= '</ul>';
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $html);
        }

        return $html;
    }

    public function getTypeData($type)
    {
        $newsMenu = NewsMenu::find([
            'columns' => 'id, name, slug, level, parent_id, icon_type, icon, font_class, summary',
            'conditions' => 'subdomain_id = ' . $this->_subdomain_id . ' AND language_id = '. $this->_lang_id .' AND '. $type .' = "Y" AND active="Y" AND deleted = "N"',
            'order' => 'sort ASC, id DESC'
        ]);

        return $newsMenu;
    }

    /**
     * get Category child
     * @param  integer $parentId [description]
     * @param  null|array  $options  [description]
     * @return mixed
     */
    public function getCategoryChild($parentId = 0, $options = null)
    {
        $hasKey = __FUNCTION__ . '_' . $parentId;
        if (!empty($options)) {
            foreach ($options as $key => $option) {
                $hasKey .= '_' . $key;
            }
        }

        $categories = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($categories === null) {
            $subdomainId = $this->_subdomain_id;
            $conditions = "subdomain_id = $subdomainId AND parent_id = $parentId AND language_id = $this->_lang_id AND active = 'Y' AND deleted = 'N'";

            $categories = NewsMenu::find([
                "columns" => "id, name, slug",
                "conditions" => $conditions,
                "order" => "sort ASC, id DESC"
            ]);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $categories);
        }

        return $categories;
    }
}
