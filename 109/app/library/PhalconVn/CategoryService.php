<?php
namespace Modules\PhalconVn;

use Modules\Models\Category;
use Modules\Models\ProductContent;

class CategoryService extends BaseService
{
    /**
     *
     * @var cache key $cacheKey
     */
    protected $cacheKey;

    public function __construct()
    {
        parent::__construct();
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
        $this->_folder = $this->mainGlobal->getDomainFolder();
        $this->cacheKey = 'product:' . $this->_subdomain_id;
    }

    /**
     * Get multi level category
     * 
     * @param $parentId
     * @param $level
     * @param $options
     * 
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
            $categories = Category::find(["columns" => "id, name, slug, level, parent_id, icon_type, icon, font_class", "order" => "sort ASC, id DESC", "conditions" => "parent_id = " . $parentId . " AND language_id = $this->_lang_id AND subdomain_id = " . $this->_subdomain_id . " AND active='Y' AND deleted = 'N'"]);
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
                    $iconType = $category->icon_type;
                    $link = ($this->_lang_code == 'vi') ? $tag->site_url($category->slug) : $tag->site_url($this->_lang_code . '/' . $category->slug);
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
                            $html .= '<i class="fa fa-'. $icon .'" aria-hidden="true"></i>';
                        } else {
                            if ($iconType == 2 && !empty($category->icon) && file_exists('files/icon/' . $this->_folder . '/' . $category->icon)) {
                                $html .= '<img src="/files/icon/'. $this->_folder . '/' . $category->icon .'" alt="'. $category->name .'">';
                            } else {
                                if ($iconType == 1 && !empty($category->font_class)) {
                                    $html .= '<i class="fa fa-'. $category->font_class .'"></i>';
                                }
                            }
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
     * 
     * @param $parentId
     * @param $level
     * @param $options
     * 
     * @return string
     */
    public function recursiveVerticalMenu($parentId = 0, $options = null)
    {
        $cfDisplayType = $this->config_service->getConfigItemDetail('_cf_select_display_menu_category_left');
        $hasKey = __FUNCTION__ . '_' . $parentId;
        if (!empty($options)) {
            foreach ($options as $key => $option) {
                $hasKey .= '_' . $key;
            }
        }

        $hasKey .= '_' . $cfDisplayType;
        $html = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($html === null) {
            $plus = $cfDisplayType == 1 ? 'minus' : 'plus';
            $display = $cfDisplayType == 1 ? ' style="display:block"' : '';
            $tag = new Tag();
            $categories = Category::find(["columns" => "id, name, slug, level, parent_id, icon_type, icon, font_class", "order" => "sort ASC, id DESC", "conditions" => "parent_id = " . $parentId . " AND subdomain_id = " . $this->_subdomain_id . " AND language_id = $this->_lang_id AND active='Y' AND deleted = 'N'"]);
            $html = null;
            if (count($categories) > 0) {
                $i = 0;
                $html = '<ul'. $display .'>';
                foreach ($categories as $category) {
                    $level = (isset($options['type']) && $options['type'] == 'product') ? $category->level + 1 : $category->level;
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
                    
                    if (isset($options['router'])) {
                        $link = ($this->_lang_code == 'vi') ? $tag->site_url($options['router'] . '/' . $category->slug) : $tag->site_url($options['router'] . '/' . $this->_lang_code . '/' . $category->slug);
                    } else {
                        $link = ($this->_lang_code == 'vi') ? $tag->site_url($category->slug) : $tag->site_url($this->_lang_code . '/' . $category->slug);
                    }
                    
                    if ($category->parent_id == $parentId) {
                        $html.= '<li><a href="' . $link .'">' . $icon . '<h3>' . $category->name . '</h3></a>';
                      
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

    /**
     * Get recursive category
     *
     * @param int $parentId
     * @param string $space
     * @param array $trees
     * 
     * @return object
     */
    public function recursiveCategory($parentId = 0, $space = "", $trees = array())
    {
        if (!$trees) {
            $trees = [];
        }

        $result = Category::find(["order" => "sort ASC, id DESC", "conditions" => "parent_id = " . $parentId . " AND subdomain_id = " . $this->_subdomain_id . " AND active = 'Y' AND deleted = 'N'"]);
        $treesObj = [];
        if (count($result) > 0) {
            foreach ($result as $row) {
                $trees[] = ['id' => $row->id, 'parent_id' => $row->parent_id, 'level' => $row->level, 'name' => $space . $row->name, 'slug' => $row->slug, ];
                $trees = $this->recursiveCategory($row->id, $space, $trees);
            }
        }

        if (!empty($trees)) {
            foreach ($trees as $tree) {
                $tree = (object)$tree;
                $treesObj[] = $tree;
            }
        }

        return $treesObj;
    }

    /**
     * get recursive category combo menu
     * 
     * @param  integer $parentId
     * @param  array   $result
     * 
     * @return array
     */
    public function recursiveCategoryCombo($parentId = 0, $result = [])
    {
        $hasKey = __FUNCTION__ . '_' . $parentId;
        $result = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array']);
        if ($result === null) {
            $categories = Category::find(["columns" => "id, name, slug, parent_id, level, icon_type, icon, font_class", "order" => "sort ASC, id DESC", "conditions" => "parent_id = $parentId AND subdomain_id = " . $this->_subdomain_id . " AND language_id = $this->_lang_id AND deleted = 'N'"]);
            if (count($categories) > 0) {
                foreach ($categories as $key => $category) {
                    $result[] = $category->toArray();
                    $categoryChilds = Category::find(["columns" => "id, name, slug, parent_id, level, icon_type, icon, font_class", "order" => "sort ASC, id DESC", "conditions" => "parent_id = " . $category->id . " AND subdomain_id = " . $this->_subdomain_id . " AND language_id = $this->_lang_id AND deleted = 'N'"]);
                    if (count($categoryChilds) > 0) {
                        /*$objChild = [];
                        foreach ($categoryChilds->toArray() as $categoryChild) {
                            $objChild[] = $categoryChild;
                        }*/
                        $result[$key]['child'] = $categoryChilds->toArray();
                    }

                    // $this->recursiveCategoryCombo($category->id, $result);
                }
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $result);
        }
        
        return $result;
    }

    /**
     * nested list category
     * 
     * @param  integer $parentId
     * @return [type]            [description]
     */
    public function nestedCategory($parentId = 0)
    {
        $hasKey = __FUNCTION__ . '_' . $parentId;
        $trees = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array']);
        if ($trees === null) {
            $subdomain_id = $this->_subdomain_id;
            $category = Category::findFirst([
                "columns" => "id, name, slug, parent_id, level", "order" => "sort ASC, id DESC",
                "conditions" => "id = " . $parentId . " AND subdomain_id = $subdomain_id AND language_id = $this->_lang_id AND active='Y' AND deleted = 'N'"
            ]);
            
            $trees = [];

            if ($category) {
                if ($category->parent_id != 0) {
                    $categoryParent = Category::findFirst([
                        'columns' => 'id',
                        'conditions' => 'id = '. $category->parent_id .' AND active = "Y"'
                    ]);

                    if (!$categoryParent) {
                        return $trees;
                    }
                }

                $category = $category->toArray();
                $trees = array("id" => $category["id"], "parent_id" => $category["parent_id"], "name" => $category["name"], "slug" => $category["slug"], "level" => $category["level"]);
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
        if (!$trees) {
            $trees = array();
        }
        $trees[] = $parentId;
        $categories = Category::find(["columns" => "id", "order" => "sort ASC, id DESC", "conditions" => "parent_id = " . $parentId . " AND subdomain_id = $subdomain_id AND active='Y' AND deleted = 'N'"]);
        $treesObj = array();
        if (!empty($categories)) {
            foreach ($categories as $category) {
                $trees[] = $category->id;
                $trees = $this->getCategoryTreeIdOld($category->id, $trees);
            }
        }
        if (!empty($trees)) {
            foreach ($trees as $tree) {
                $treesObj[] = $tree;
            }
        }
        $treesObj = array_unique($treesObj);
        return $treesObj;
    }

    /**
     * get list Category id
     * 
     * @param  integer $parentId
     * @param  array   $trees   
     * @param  null|array  $options 
     * 
     * @return array           
     */
    public function getCategoryTreeId($parentId = 0, $trees = array(), $options = null)
    {
        $subdomain_id = $this->_subdomain_id;
        $active = (isset($options['notActive']) && $options['notActive'] == true) ? '' : ' AND active = "Y"';
        if ($parentId != 0) {
            $trees[] = $parentId;
        }
        
        $categories = Category::find(["columns" => "id", "conditions" => "subdomain_id = $this->_subdomain_id AND language_id = $this->_lang_id AND parent_id = " . $parentId . $active ." AND deleted = 'N'"]);
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
     * Get category parent id equal 0
     *
     * @return mixed
     */
    public function getCategoryParent()
    {
        $hasKey = __FUNCTION__;
        $categories = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($categories === null) {
            $subdomainId = $this->_subdomain_id;
            $categories = Category::find(["columns" => "id, name, slug, icon_type, icon, font_class", "conditions" => "subdomain_id = $subdomainId AND language_id = $this->_lang_id AND parent_id = 0 AND active = 'Y' AND deleted = 'N'", "order" => "sort ASC, id DESC"]);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $categories);
        }
        
        return $categories;
    }

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
            if (isset($options['picture']) && $options['picture'] == true) {
                $conditions .= " AND picture = 'Y'";
            }

            $categories = Category::find([
                "columns" => "id, name, slug, banner",
                "conditions" => $conditions,
                "order" => "sort ASC, id DESC"
            ]);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $categories);
        }

        return $categories;
    }

    /**
     * Get category tree product detail
     *
     * @param int $productId
     * @return mixed
     */
    public function getCategoryProductDetail($productId)
    {
        $hasKey = __FUNCTION__ . '_' . $productId;
        $categories = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($categories === null) {
            $categories = Category::query()->columns(["Modules\Models\Category.id", "name", "slug", "parent_id"])
            ->join("Modules\Models\TmpProductCategory", "tmp.category_id = Modules\Models\Category.id", "tmp")
            ->where("language_id = :language_id:")
            ->andWhere("product_id = :product_id:")
            ->andWhere("active = :active:")
            ->andWhere("deleted = :deleted:")
            ->bind(["language_id" => $this->_lang_id,"product_id" => $productId, "active" => "Y", "deleted" => "N"])
            ->orderBy("level DESC, Modules\Models\Category.id DESC")
            ->execute();

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $categories);
        }

        return $categories;
    }

    /**
     * Get list category type
     * 
     * @param $type
     * @return mixed
     */
    public function getCategoryType($type = 'hot')
    {
        $hasKey = __FUNCTION__ . '_' . $type;
        $categories = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($categories === null) {
            $categories = Category::find([
                "columns" => "id, name, slug, icon_type, icon, banner, banner_md_sole, content, font_class",
                "conditions" => "subdomain_id = $this->_subdomain_id AND language_id = $this->_lang_id AND ". $type ." = 'Y' AND active='Y' AND deleted = 'N'",
                "order" => "sort ASC, id DESC"
            ]);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $categories);
            $categories = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        }

        return $categories;
    }

    /**
     * Get category group sole
     * 
     * @param  string $type
     * @return array      
     */
    public function getCategoryGroupSole($type = 'hot')
    {
        $categories = $this->getCategoryType($type);
        $result = $this->general->arraySlice($categories, 0, 2);

        return $result;
    }
}
