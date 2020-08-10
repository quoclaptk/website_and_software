<?php

namespace Modules\PhalconVn;

use Modules\Models\Menu;
use Modules\Models\MenuItem;
use Modules\Models\ModuleItem;
use Modules\Models\Category;
use Modules\Models\NewsMenu;

class MenuService extends BaseService
{
    /**
     * @var cache key $cacheKey
     */
    protected $cacheKey;

    public function __construct()
    {
        parent::__construct();
        $this->cacheKey = 'general:' . $this->_subdomain_id;
    }

    public function getMenuInfo($menuModuleId)
    {
        $moduleItemCurrent = ModuleItem::findFirstById($menuModuleId);
        if ($moduleItemCurrent->subdomain_id != $this->_subdomain_id) {
            $moduleItem = ModuleItem::findFirst([
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND type = '". $moduleItemCurrent->type ."' AND name = '". $moduleItemCurrent->name ."' AND active = 'Y' AND deleted = 'N'"
            ]);
            if ($moduleItem) {
                $moduleItemId = $moduleItem->id;
            }
        } else {
            $moduleItemId = $menuModuleId;
        }
        if (isset($moduleItemId)) {
            return Menu::findFirst([
                "columns" => "id, name, main, style",
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND module_item_id = $moduleItemId AND language_id = $this->_lang_id AND active = 'Y' AND deleted = 'N'"
            ]);
        }
    }

    public function getMenuInfoWidthId($menuId)
    {
        return Menu::findFirst([
            "columns" => "id, name, main, style",
            "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND id = $menuId AND active = 'Y' AND deleted = 'N'"
        ]);
    }

    /**
     * get menu item detail
     * 
     * @param  integer $menuId 
     * 
     * @return array        
     */
    public function getMenuItem($menuId)
    {
        $hasKey = __FUNCTION__ . '_' . $menuId;
        $result = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($result === null) {
            $menuItems = MenuItem::find([
                "columns" => "id, name, url, other_url, sort, module_name, module_id, font_class, photo, icon_type, new_blank",
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND menu_id = $menuId AND language_id = $this->_lang_id AND active = 'Y' AND deleted = 'N'",
                "order" => "sort ASC, id DESC"
            ]);

            $result = [];
            if (count($menuItems) > 0) {
                foreach ($menuItems as $key => $menuItem) {
                    $result[] = $menuItem->toArray();
                    if ($menuItem->module_name == 'category' && empty($menuItem->font_class) && empty($menuItem->photo)) {
                        $category = Category::findFirst([
                            'columns' => 'icon_type, font_class, icon',
                            'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND id = '. $menuItem->module_id .''
                        ]);

                        if ($category && (!empty($category->font_class) || !empty($category->icon))) {
                            $result[$key]['icon_type_category'] = $category->icon_type;
                            $result[$key]['font_class_category'] = $category->font_class;
                            $result[$key]['icon_category'] = $category->icon;
                        }
                    }

                    if ($menuItem->module_name == 'news_menu' && empty($menuItem->font_class) && empty($menuItem->photo)) {
                        $newsMenu = NewsMenu::findFirst([
                            'columns' => 'icon_type, font_class, icon',
                            'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND id = '. $menuItem->module_id .''
                        ]);

                        if ($newsMenu && (!empty($newsMenu->font_class) || !empty($newsMenu->icon))) {
                            $result[$key]['icon_type_category'] = $newsMenu->icon_type;
                            $result[$key]['font_class_category'] = $newsMenu->font_class;
                            $result[$key]['icon_category'] = $newsMenu->icon;
                        }
                    }

                    $result[$key] = (object) $result[$key];
                }
            }
            
            $result = (object) $result;
            $this->_setHasKeyValue($this->cacheKey, $hasKey, $result, ['to_array' => false]);
        }  

        return $result;
    }

    /**
     * Get main menu info
     * 
     * @return mixed
     */
    public function getMenuInfoMain()
    {
        $hasKey = __FUNCTION__;
        $menu = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($menu === null) {
            $menu = Menu::findFirst([
                "columns" => "id, name, main, style",
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND main = 'Y' AND language_id = $this->_lang_id AND active = 'Y' AND deleted = 'N'"
            ]);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $menu);
        }

        return $menu;
    }
}
