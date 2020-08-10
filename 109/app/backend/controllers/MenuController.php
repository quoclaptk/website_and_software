<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Category;
use Modules\Models\Layout;
use Modules\Models\Menu;
use Modules\Models\MenuItem;
use Modules\Models\ModuleItem;
use Modules\Models\NewsType;
use Modules\Models\NewsMenu;
use Modules\Models\LandingPage;
use Modules\Models\Position;
use Modules\Models\TmpLayoutModule;
use Modules\Models\TmpPositionModuleItem;
use Modules\Forms\MenuForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Text as TextRandom;
use Phalcon\Image\Adapter\GD;

class MenuController extends BaseController
{
    public function onConstruct()
    {
        parent::onConstruct();
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Menu';
    }

    public function __indexAction()
    {
        $list = Menu::find([
            "order" => "sort ASC, id DESC",
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
        ]);

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator([
            "data" => $list,
            "limit" => 30,
            "page" => $numberPage
        ]);

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
        $this->view->pick($this->_getControllerName() . '/index');
    }

    public function createAction()
    {
        $form = new MenuForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $item = new Menu();

            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'module_item_id' => 0,
                'name' => $this->request->getPost('name'),
                'sort' => 1,
                'style' => $this->request->getPost('style'),
                'main' => $this->request->getPost('main'),
                'active' => $this->request->getPost('active')
            ];

            $item->assign($data);

            if ($item->save()) {
                
                $id = $item->id;
                $position_id = $this->request->getPost('position');
                $module_item = new ModuleItem();
                $module_item->assign([
                    'module_group_id' => 0,
                    'subdomain_id' => $this->_get_subdomainID(),
                    'parent_id' => 0,
                    'name' => $this->request->getPost('name'),
                    'sort' => $this->request->getPost('sort'),
                    'type' => 'menu',
                    'active' => $this->request->getPost('active')
                ]);

                if ($module_item->save()) {
                    $module_item_id = $module_item->id;
                    $item = Menu::findFirst([
                        "conditions" => "id = $id"
                    ]);
                    $item->assign(['module_item_id' => $module_item_id]);
                    $item->save();

                    if (!empty($position_id)) {
                        foreach ($position_id as $row) {
                            //insert tmp postiion module item
                            $tmp_position_module_item = new TmpPositionModuleItem();
                            $tmp_position_module_item->assign([
                                'subdomain_id' => $this->_get_subdomainID(),
                                'module_item_id' => $module_item_id,
                                'position_id' => $row
                            ]);

                            $tmp_position_module_item->save();

                            $layout = Layout::find([
                                'conditions' => 'active = "Y"'
                            ]);
                            $layout_module = TmpLayoutModule::findFirst([
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND position_id = $row",
                                "order" => "sort DESC"
                            ]);
                            $sort = (count($layout_module) > 0) ? $layout_module->sort + 1 : 1;

                            foreach ($layout as $rowLayout) {
                                //insert tmp layout module
                                $tmp_layout_module = new TmpLayoutModule();
                                $tmp_layout_module->assign([
                                    'subdomain_id' => $module_item->subdomain_id,
                                    'layout_id' => $rowLayout->id,
                                    'module_item_id' => $module_item->id,
                                    'position_id' => $row,
                                    'sort' => $sort
                                ]);
                                $tmp_layout_module->save();
                            }
                        }
                    }
                }

                $this->flashSession->success($this->_message["add"]);

                $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $position = Position::find(
            [
                'columns' => 'id, name',
                "conditions" => "active = 'Y'",
                'order' => 'sort ASC, id DESC'
            ]
        );

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->position = $position;
        $this->view->pick($this->_getControllerName() . '/create');
    }

    public function updateAction(int $id, int $page = 1)
    {
        $item = Menu::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $tmp_position_module_item_c = TmpPositionModuleItem::findByModuleItemId($item->module_item_id);
        $tmp_position_module_item_arr = array();
        if (!empty($tmp_position_module_item_c)) {
            foreach ($tmp_position_module_item_c as $row) {
                $tmp_position_module_item_arr[] = $row->position_id;
            }
        }

        $form = new MenuForm($item, ['edit' => true]);
        if ($this->request->isPost()) {
            $sort = $this->request->getPost('sort');
            $icon_type = $this->request->getPost('icon_type');
            if ($this->request->hasPost('display_type')) {
                $item->display_type = $this->request->getPost('display_type');
                $item->save();
            }
            
            $menuItems = MenuItem::find([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
                "order" => "sort ASC, id DESC"
            ]);

            $i = 0;
            foreach ($menuItems as $menuItem) {
                if ($sort != '') {
                    $menuItem->sort = $sort[$i];
                }
                $menuItem->icon_type = $icon_type[$i];
                $menuItem->save();
                $menuItemLangs = MenuItem::findByDependId($menuItem->id);
                if (count($menuItemLangs) > 0) {
                    foreach ($menuItemLangs as $menuItemLang) {
                        $menuItemLang->sort = $menuItem->sort;
                        $menuItemLang->icon_type = $menuItem->icon_type;
                        $menuItemLang->save();
                    }
                }

                $i++;
            }

            

            $this->flashSession->success($this->_message["edit"]);
            $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id . '/' . $page;

            $this->response->redirect($url);
        }

        $position = Position::find(
            [
                'columns' => 'id, name',
                "conditions" => "active = 'Y'",
                'order' => 'sort ASC, id DESC'
            ]
        );

        $categories = Category::find([
            'columns' => 'id, name, slug',
            'conditions' => 'Modules\Models\Category.subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1 AND parent_id = 0 AND active = "Y"',
            'order' => 'sort ASC, id DESC'
        ]);

        $news_type = NewsType::find([
            'columns' => 'id, name, slug',
            'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND active = "Y"',
            'order' => 'sort ASC, id DESC'
        ]);

        $news_menu = NewsMenu::find([
            'columns' => 'id, name, slug',
            'conditions' => 'Modules\Models\NewsMenu.subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1 AND active = "Y"',
            'order' => 'sort ASC, id DESC'
        ]);

        $landingPages = LandingPage::find([
            'columns' => 'id, name, slug',
            'conditions' => 'Modules\Models\LandingPage.subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1 AND active = "Y"',
            'order' => 'sort ASC, id DESC'
        ]);

        $productArray = [
            'module_name' => 'product',
            'name' => 'Sản phẩm',
            'url' => 'san-pham',
            'module_id' => 0,
            'parent_id' => 0,
            'level' => 0,
            'child' => ''
//            'child' => $this->recursive(0)
        ];

        $menuArray = array();
        $menuArray[] = [
            'module_name' => 'index',
            'name' => 'Trang chủ',
            'url' => '/',
            'module_id' => 0,
            'parent_id' => 0,
            'level' => 0,
            'child' => ''
        ];

        if (count($categories) > 0) {
            foreach ($categories as $row) {
                $menuArray[] = [
                    'module_name' => 'category',
                    'name' => $row->name,
                    'url' => $row->slug,
                    'module_id' => $row->id,
                    'parent_id' => 0,
                    'level' => 0,
                    'child' => ''
                ];
            }
        }

        if (count($news_type) > 0) {
            foreach ($news_type as $row) {
                $menuArray[] = [
                    'module_name' => 'news_type',
                    'name' => $row->name,
                    'url' => $row->slug,
                    'module_id' => $row->id,
                    'parent_id' => 0,
                    'level' => 0,
                    'child' => ''
                ];
            }
        }

        if (count($news_menu) > 0) {
            foreach ($news_menu as $row) {
                $menuArray[] = [
                    'module_name' => 'news_menu',
                    'name' => $row->name,
                    'url' => $row->slug,
                    'module_id' => $row->id,
                    'parent_id' => 0,
                    'level' => 0,
                    'child' => ''
                ];
            }
        }

        if (count($landingPages) > 0) {
            foreach ($landingPages as $row) {
                $menuArray[] = [
                    'module_name' => 'landing_page',
                    'name' => $row->name,
                    'url' => $row->slug,
                    'module_id' => $row->id,
                    'parent_id' => 0,
                    'level' => 0,
                    'child' => ''
                ];
            }
        }

        $menuArray[] = $productArray;

        $menuArray[] = [
            'module_name' => 'clip',
            'name' => 'Video',
            'url' => 'video',
            'module_id' => 0,
            'parent_id' => 0,
            'level' => 0,
            'child' => ''
        ];

        $menuArray[] = [
            'module_name' => 'contact',
            'name' => 'Liên hệ',
            'url' => 'lien-he',
            'module_id' => 0,
            'parent_id' => 0,
            'level' => 0,
            'child' => ''
        ];

        $menuArray[] = [
            'module_name' => 'customer_comment',
            'name' => $this->_word['_y_kien_khach_hang'],
            'url' => 'y-kien-khach-hang',
            'module_id' => 0,
            'parent_id' => 0,
            'level' => 0,
            'child' => ''
        ];

        $menuArray[] = [
            'module_name' => 'subdomain_list',
            'name' => $this->_word['_du_an_da_thuc_hien'],
            'url' => 'du-an-da-thuc-hien',
            'module_id' => 0,
            'parent_id' => 0,
            'level' => 0,
            'child' => ''
        ];

        $menu_item = $this->recursive_menu($id, 0);
//        $this->print_array($menu_item);die;

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->item = $item;
        $this->view->menu_item = $menu_item;
        $this->view->position = $position;
        $this->view->menuArray = $menuArray;
        $this->view->folder = $this->_get_subdomainFolder();
        $this->view->tmp_position_module_item_c = $tmp_position_module_item_c;
        $this->view->tmp_position_module_item_arr = $tmp_position_module_item_arr;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function addMenuAction($id)
    {
        $menu = Menu::findFirstById($id);
        if ($menu) {
            $menu_item_list = MenuItem::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
                'order' => 'sort DESC'
            ]);

            if ($this->request->isPost() != '') {
                
                $request = $this->request->getPost();
                $home_menu = $request['index'];
                $news_type_menu = $request['news_type'];
                $news_menu_menu = $request['news_menu'];
                $landing_page_menu = $request['landing_page'];
                $product_menu = $request['product'];
                $category_menu = (!empty($request['category'])) ? $request['category'] : "";
                $video = $request['clip'];
                $contact_menu = $request['contact'];
                $customer_comment_menu = $request['customer_comment'];
                $subdomain_list_menu = $request['subdomain_list'];
                //            $this->print_array($news_type_menu);die;

                $rowHome = $home_menu[0];
                if (isset($rowHome['module_id'])) {
                    $menu_item = new MenuItem();
                    $sort = (!empty($menu_item_list)) ? $menu_item_list->sort + 1 : 1;

                    $data = [
                        'subdomain_id' => $this->_get_subdomainID(),
                        'menu_id' => $id,
                        'parent_id' => $rowHome['parent_id'],
                        'module_id' => $rowHome['module_id'],
                        'level' => $rowHome['level'],
                        'module_name' => $rowHome['module_name'],
                        'name' => $rowHome['name'],
                        'url' => $rowHome['url'],
                        'active' => 'Y',
                        'sort' => $sort
                    ];
                    $menu_item->assign($data);
                    if ($menu_item->save()) {
                        if (count($this->_tmpSubdomainLanguages) > 0) {
                            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                $langId = $tmp->language->id;
                                $langCode = $tmp->language->code;
                                $menuLang = Menu::findFirst([
                                    "conditions" => "language_id = $langId AND depend_id = $id"
                                ]);
                                if ($menuLang) {
                                    $menuItemVi = $menu_item->toArray();
                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_nha'];
                                    $menuItemVi['language_id'] = $langId;
                                    $menuItemVi['menu_id'] = $menuLang->id;
                                    $menuItemVi['depend_id'] = $menuItemVi['id'];
                                    unset($menuItemVi['id']);
                                    unset($menuItemVi['created_at']);
                                    unset($menuItemVi['modified_in']);
                                    $menuItemLang = new MenuItem();
                                    $menuItemLang->assign($menuItemVi);
                                    if (!$menuItemLang->save()) {
                                        foreach ($menuItemLang->getMessages() as $message) {
                                            $this->flashSession->error($message);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    unset($data);
                }

                if (!empty($news_type_menu)) {
                    foreach ($news_type_menu as $row) {
                        if (isset($row['module_id'])) {
                            $menu_item_list = MenuItem::findFirst([
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
                                'order' => 'sort DESC'
                            ]);
                            $menu_item = new MenuItem();
                            $sort = (count($menu_item_list) > 0) ? $menu_item_list->sort + 1 : 1;

                            $data = [
                                'subdomain_id' => $this->_get_subdomainID(),
                                'menu_id' => $id,
                                'parent_id' => $row['parent_id'],
                                'module_id' => $row['module_id'],
                                'level' => $row['level'],
                                'module_name' => $row['module_name'],
                                'name' => $row['name'],
                                'url' => $row['url'],
                                'active' => 'Y',
                                'sort' => $sort
                            ];
                            $menu_item->assign($data);
                            $menu_item->save();
                            unset($data);
                        }
                    }
                }

                if (!empty($news_menu_menu)) {
                    foreach ($news_menu_menu as $row) {
                        if (isset($row['module_id'])) {
                            $menu_item_list = MenuItem::findFirst([
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
                                'order' => 'sort DESC'
                            ]);
                            $menu_item = new MenuItem();
                            $sort = (count($menu_item_list) > 0) ? $menu_item_list->sort + 1 : 1;

                            $data = [
                                'subdomain_id' => $this->_get_subdomainID(),
                                'menu_id' => $id,
                                'parent_id' => $row['parent_id'],
                                'module_id' => $row['module_id'],
                                'level' => $row['level'],
                                'module_name' => $row['module_name'],
                                'name' => $row['name'],
                                'url' => $row['url'],
                                'active' => 'Y',
                                'sort' => $sort
                            ];
                            $menu_item->assign($data);
                            if ($menu_item->save()) {
                                if ($menu->main == 'Y') {
                                    $newsMenu = NewsMenu::findFirstById($menu_item->module_id);
                                    $newsMenu->menu = 'Y';
                                    $newsMenu->save();
                                }

                                if (count($this->_tmpSubdomainLanguages) > 0) {
                                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                        $langId = $tmp->language->id;
                                        $langCode = $tmp->language->code;
                                        $menuLang = Menu::findFirst([
                                            "conditions" => "language_id = $langId AND depend_id = $id"
                                        ]);
                                        if ($menuLang) {
                                            $menuItemVi = $menu_item->toArray();
                                            $newsMenuLang = NewsMenu::findFirst([
                                                'conditions' => 'depend_id = ' . $menuItemVi['module_id'] . ' AND language_id = '. $langId .''
                                            ]);
                                            if ($newsMenuLang) {
                                                if ($menu->main == 'Y') {
                                                    $newsMenuLang->menu = 'Y';
                                                    $newsMenuLang->save();
                                                }

                                                $menuItemVi['module_id'] = $newsMenuLang->id;
                                                $menuItemVi['name'] = $newsMenuLang->name;
                                                $menuItemVi['url'] = $newsMenuLang->slug;

                                                $menuItemVi['language_id'] = $langId;
                                                $menuItemVi['menu_id'] = $menuLang->id;
                                                $menuItemVi['depend_id'] = $menuItemVi['id'];
                                                unset($menuItemVi['id']);
                                                unset($menuItemVi['created_at']);
                                                unset($menuItemVi['modified_in']);
                                                $menuItemLang = new MenuItem();
                                                $menuItemLang->assign($menuItemVi);
                                                if (!$menuItemLang->save()) {
                                                    foreach ($menuItemLang->getMessages() as $message) {
                                                        $this->flashSession->error($message);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            unset($data);
                        }
                    }
                }

                if (!empty($landing_page_menu)) {
                    foreach ($landing_page_menu as $row) {
                        if (isset($row['module_id'])) {
                            $menu_item_list = MenuItem::findFirst([
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
                                'order' => 'sort DESC'
                            ]);
                            $menu_item = new MenuItem();
                            $sort = (count($menu_item_list) > 0) ? $menu_item_list->sort + 1 : 1;

                            $data = [
                                'subdomain_id' => $this->_get_subdomainID(),
                                'menu_id' => $id,
                                'parent_id' => $row['parent_id'],
                                'module_id' => $row['module_id'],
                                'level' => $row['level'],
                                'module_name' => $row['module_name'],
                                'name' => $row['name'],
                                'url' => $row['url'],
                                'active' => 'Y',
                                'sort' => $sort
                            ];
                            $menu_item->assign($data);
                            if ($menu_item->save()) {
                                if ($menu->main == 'Y') {
                                    $langdingPage = LandingPage::findFirstById($menu_item->module_id);
                                    $langdingPage->menu = 'Y';
                                    $langdingPage->save();
                                }

                                if (count($this->_tmpSubdomainLanguages) > 0) {
                                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                        $langId = $tmp->language->id;
                                        $langCode = $tmp->language->code;
                                        $menuLang = Menu::findFirst([
                                            "conditions" => "language_id = $langId AND depend_id = $id"
                                        ]);
                                        if ($menuLang) {
                                            $menuItemVi = $menu_item->toArray();
                                            $langdingPageLang = LandingPage::findFirst([
                                                'conditions' => 'depend_id = ' . $menuItemVi['module_id'] . ' AND language_id = '. $langId .''
                                            ]);
                                            if ($langdingPageLang) {
                                                if ($menu->main == 'Y') {
                                                    $langdingPageLang->menu = 'Y';
                                                    $langdingPageLang->save();
                                                }

                                                $menuItemVi['module_id'] = $langdingPageLang->id;
                                                $menuItemVi['name'] = $langdingPageLang->name;
                                                $menuItemVi['url'] = $langdingPageLang->slug;

                                                $menuItemVi['language_id'] = $langId;
                                                $menuItemVi['menu_id'] = $menuLang->id;
                                                $menuItemVi['depend_id'] = $menuItemVi['id'];
                                                unset($menuItemVi['id']);
                                                unset($menuItemVi['created_at']);
                                                unset($menuItemVi['modified_in']);
                                                $menuItemLang = new MenuItem();
                                                $menuItemLang->assign($menuItemVi);
                                                if (!$menuItemLang->save()) {
                                                    foreach ($menuItemLang->getMessages() as $message) {
                                                        $this->flashSession->error($message);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            unset($data);
                        }
                    }
                }

                $rowProduct = $product_menu[0];
                if (isset($rowProduct['module_id'])) {
                    $menu_item_list = MenuItem::findFirst([
                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
                        'order' => 'sort DESC'
                    ]);
                    $menu_item = new MenuItem();
                    $sort = (count($menu_item_list) > 0) ? $menu_item_list->sort + 1 : 1;

                    $data = [
                        'subdomain_id' => $this->_get_subdomainID(),
                        'menu_id' => $id,
                        'parent_id' => $rowProduct['parent_id'],
                        'module_id' => $rowProduct['module_id'],
                        'level' => $rowProduct['level'],
                        'module_name' => $rowProduct['module_name'],
                        'name' => $rowProduct['name'],
                        'url' => $rowProduct['url'],
                        'active' => 'Y',
                        'sort' => $sort
                    ];
                    $menu_item->assign($data);
                    $menu_item->assign($data);
                    if ($menu_item->save()) {
                        if (count($this->_tmpSubdomainLanguages) > 0) {
                            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                $langId = $tmp->language->id;
                                $langCode = $tmp->language->code;
                                $menuLang = Menu::findFirst([
                                    "conditions" => "language_id = $langId AND depend_id = $id"
                                ]);
                                if ($menuLang) {
                                    $menuItemVi = $menu_item->toArray();
                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_san_pham'];
                                    $menuItemVi['url'] = 'product';
                                    $menuItemVi['language_id'] = $langId;
                                    $menuItemVi['menu_id'] = $menuLang->id;
                                    $menuItemVi['depend_id'] = $menuItemVi['id'];
                                    unset($menuItemVi['id']);
                                    unset($menuItemVi['created_at']);
                                    unset($menuItemVi['modified_in']);
                                    $menuItemLang = new MenuItem();
                                    $menuItemLang->assign($menuItemVi);
                                    if (!$menuItemLang->save()) {
                                        foreach ($menuItemLang->getMessages() as $message) {
                                            $this->flashSession->error($message);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    $product_menu_id = $menu_item->id;
                    unset($data);
                }

                if (!empty($category_menu)) {
                    foreach ($category_menu as $row) {
                        if (isset($row['module_id'])) {
                            $menu_item_list = MenuItem::findFirst([
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
                                'order' => 'sort DESC'
                            ]);
                            $menu_item = new MenuItem();
                            $sort = (count($menu_item_list) > 0) ? $menu_item_list->sort + 1 : 1;

                            $data = [
                                'subdomain_id' => $this->_get_subdomainID(),
                                'menu_id' => $id,
                                'parent_id' => $row['parent_id'],
                                'module_id' => $row['module_id'],
                                'level' => $row['level'],
                                'module_name' => $row['module_name'],
                                'name' => $row['name'],
                                'url' => $row['url'],
                                'active' => 'Y',
                                'sort' => $sort
                            ];
                            $menu_item->assign($data);
                            if ($menu_item->save()) {
                                if ($menu->main == 'Y') {
                                    $category = Category::findFirstById($menu_item->module_id);
                                    $category->menu = 'Y';
                                    $category->save();
                                }
                                
                                if (count($this->_tmpSubdomainLanguages) > 0) {
                                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                        $langId = $tmp->language->id;
                                        $langCode = $tmp->language->code;
                                        $menuLang = Menu::findFirst([
                                            "conditions" => "language_id = $langId AND depend_id = $id"
                                        ]);
                                        if ($menuLang) {
                                            $menuItemVi = $menu_item->toArray();
                                            $categoryLang = Category::findFirst([
                                                'conditions' => 'depend_id = ' . $menuItemVi['module_id'] . ' AND language_id = '. $langId .''
                                            ]);
                                            if ($categoryLang) {
                                                if ($menu->main == 'Y') {
                                                    $categoryLang->menu = 'Y';
                                                    $categoryLang->save();
                                                }
                                                $menuItemVi['module_id'] = $categoryLang->id;
                                                $menuItemVi['name'] = $categoryLang->name;
                                                $menuItemVi['url'] = $categoryLang->slug;
                                                $menuItemVi['language_id'] = $langId;
                                                $menuItemVi['menu_id'] = $menuLang->id;
                                                $menuItemVi['depend_id'] = $menuItemVi['id'];
                                                unset($menuItemVi['id']);
                                                unset($menuItemVi['created_at']);
                                                unset($menuItemVi['modified_in']);
                                                $menuItemLang = new MenuItem();
                                                $menuItemLang->assign($menuItemVi);
                                                if (!$menuItemLang->save()) {
                                                    foreach ($menuItemLang->getMessages() as $message) {
                                                        $this->flashSession->error($message);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            unset($data);
                        }
                    }
                }

                $rowVideo = $video[0];
                if (isset($rowVideo['module_id'])) {
                    $menu_item_list = MenuItem::findFirst([
                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
                        'order' => 'sort DESC'
                    ]);

                    $sort = (count($menu_item_list) > 0) ? $menu_item_list->sort + 1 : 1;

                    $data = [
                        'subdomain_id' => $this->_get_subdomainID(),
                        'menu_id' => $id,
                        'parent_id' => $rowVideo['parent_id'],
                        'module_id' => $rowVideo['module_id'],
                        'level' => $rowVideo['level'],
                        'module_name' => $rowVideo['module_name'],
                        'name' => $rowVideo['name'],
                        'url' => $rowVideo['url'],
                        'active' => 'Y',
                        'sort' => $sort
                    ];
                    $menu_item = new MenuItem();
                    $menu_item->assign($data);
                    if ($menu_item->save()) {
                        if (count($this->_tmpSubdomainLanguages) > 0) {
                            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                $langId = $tmp->language->id;
                                $langCode = $tmp->language->code;
                                $menuLang = Menu::findFirst([
                                    "conditions" => "language_id = $langId AND depend_id = $id"
                                ]);
                                if ($menuLang) {
                                    $menuItemVi = $menu_item->toArray();
                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_video'];
                                    $menuItemVi['language_id'] = $langId;
                                    $menuItemVi['menu_id'] = $menuLang->id;
                                    $menuItemVi['depend_id'] = $menuItemVi['id'];
                                    unset($menuItemVi['id']);
                                    unset($menuItemVi['created_at']);
                                    unset($menuItemVi['modified_in']);
                                    $menuItemLang = new MenuItem();
                                    $menuItemLang->assign($menuItemVi);
                                    if (!$menuItemLang->save()) {
                                        foreach ($menuItemLang->getMessages() as $message) {
                                            $this->flashSession->error($message);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    unset($data);
                }

                $rowContact = $contact_menu[0];
                if (isset($rowContact['module_id'])) {
                    $menu_item_list = MenuItem::findFirst([
                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
                        'order' => 'sort DESC'
                    ]);

                    $sort = (count($menu_item_list) > 0) ? $menu_item_list->sort + 1 : 1;

                    $data = [
                        'subdomain_id' => $this->_get_subdomainID(),
                        'menu_id' => $id,
                        'parent_id' => $rowContact['parent_id'],
                        'module_id' => $rowContact['module_id'],
                        'level' => $rowContact['level'],
                        'module_name' => $rowContact['module_name'],
                        'name' => $rowContact['name'],
                        'url' => $rowContact['url'],
                        'active' => 'Y',
                        'sort' => $sort
                    ];
                    $menu_item = new MenuItem();
                    $menu_item->assign($data);
                    if ($menu_item->save()) {
                        if (count($this->_tmpSubdomainLanguages) > 0) {
                            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                $langId = $tmp->language->id;
                                $langCode = $tmp->language->code;
                                $menuLang = Menu::findFirst([
                                    "conditions" => "language_id = $langId AND depend_id = $id"
                                ]);
                                if ($menuLang) {
                                    $menuItemVi = $menu_item->toArray();
                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_lien_he'];
                                    $menuItemVi['url'] = 'contact';
                                    $menuItemVi['language_id'] = $langId;
                                    $menuItemVi['menu_id'] = $menuLang->id;
                                    $menuItemVi['depend_id'] = $menuItemVi['id'];
                                    unset($menuItemVi['id']);
                                    unset($menuItemVi['created_at']);
                                    unset($menuItemVi['modified_in']);
                                    $menuItemLang = new MenuItem();
                                    $menuItemLang->assign($menuItemVi);
                                    if (!$menuItemLang->save()) {
                                        foreach ($menuItemLang->getMessages() as $message) {
                                            $this->flashSession->error($message);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    unset($data);
                }

                $rowCustomerComment = $customer_comment_menu[0];
                if (isset($rowCustomerComment['module_id'])) {
                    $menu_item_list = MenuItem::findFirst([
                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
                        'order' => 'sort DESC'
                    ]);

                    $sort = (count($menu_item_list) > 0) ? $menu_item_list->sort + 1 : 1;

                    $data = [
                        'subdomain_id' => $this->_get_subdomainID(),
                        'menu_id' => $id,
                        'parent_id' => $rowCustomerComment['parent_id'],
                        'module_id' => $rowCustomerComment['module_id'],
                        'level' => $rowCustomerComment['level'],
                        'module_name' => $rowCustomerComment['module_name'],
                        'name' => $rowCustomerComment['name'],
                        'url' => $rowCustomerComment['url'],
                        'active' => 'Y',
                        'sort' => $sort
                    ];
                    $menu_item = new MenuItem();
                    $menu_item->assign($data);
                    if ($menu_item->save()) {
                        if (count($this->_tmpSubdomainLanguages) > 0) {
                            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                $langId = $tmp->language->id;
                                $langCode = $tmp->language->code;
                                $menuLang = Menu::findFirst([
                                    "conditions" => "language_id = $langId AND depend_id = $id"
                                ]);
                                if ($menuLang) {
                                    $menuItemVi = $menu_item->toArray();
                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_y_kien_khach_hang'];
                                    $menuItemVi['url'] = 'comment';
                                    $menuItemVi['language_id'] = $langId;
                                    $menuItemVi['menu_id'] = $menuLang->id;
                                    $menuItemVi['depend_id'] = $menuItemVi['id'];
                                    unset($menuItemVi['id']);
                                    unset($menuItemVi['created_at']);
                                    unset($menuItemVi['modified_in']);
                                    $menuItemLang = new MenuItem();
                                    $menuItemLang->assign($menuItemVi);
                                    if (!$menuItemLang->save()) {
                                        foreach ($menuItemLang->getMessages() as $message) {
                                            $this->flashSession->error($message);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    unset($data);
                }

                $rowSubdomainList = $subdomain_list_menu[0];
                if (isset($rowSubdomainList['module_id'])) {
                    $menu_item_list = MenuItem::findFirst([
                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
                        'order' => 'sort DESC'
                    ]);

                    $sort = (count($menu_item_list) > 0) ? $menu_item_list->sort + 1 : 1;

                    $data = [
                        'subdomain_id' => $this->_get_subdomainID(),
                        'menu_id' => $id,
                        'parent_id' => $rowSubdomainList['parent_id'],
                        'module_id' => $rowSubdomainList['module_id'],
                        'level' => $rowSubdomainList['level'],
                        'module_name' => $rowSubdomainList['module_name'],
                        'name' => $rowSubdomainList['name'],
                        'url' => $rowSubdomainList['url'],
                        'active' => 'Y',
                        'sort' => $sort
                    ];
                    $menu_item = new MenuItem();
                    $menu_item->assign($data);
                    if ($menu_item->save()) {
                        if (count($this->_tmpSubdomainLanguages) > 0) {
                            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                $langId = $tmp->language->id;
                                $langCode = $tmp->language->code;
                                $menuLang = Menu::findFirst([
                                    "conditions" => "language_id = $langId AND depend_id = $id"
                                ]);
                                if ($menuLang) {
                                    $menuItemVi = $menu_item->toArray();
                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_du_an'];
                                    $menuItemVi['url'] = 'project';
                                    $menuItemVi['language_id'] = $langId;
                                    $menuItemVi['menu_id'] = $menuLang->id;
                                    $menuItemVi['depend_id'] = $menuItemVi['id'];
                                    unset($menuItemVi['id']);
                                    unset($menuItemVi['created_at']);
                                    unset($menuItemVi['modified_in']);
                                    $menuItemLang = new MenuItem();
                                    $menuItemLang->assign($menuItemVi);
                                    if (!$menuItemLang->save()) {
                                        foreach ($menuItemLang->getMessages() as $message) {
                                            $this->flashSession->error($message);
                                        }
                                    }
                                }
                            }
                        }
                    }

                    unset($data);
                }

                // update elastic
                $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'menu_item']);
                $this->flashSession->success('Thêm menu thành công');
                $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;

                $this->response->redirect($url);
            }
        }
    }

    public function addMenuStaticAction($id)
    {
        $menu_item_list = MenuItem::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = $id AND language_id = 1 AND deleted = 'N'",
            'order' => 'sort DESC'
        ]);

        if ($this->request->isPost() != '') {
            $sort = (count($menu_item_list) > 0) ? $menu_item_list->sort + 1 : 1;
            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'menu_id' => $id,
                'parent_id' => 0,
                'module_id' => 0,
                'level' => 0,
                'module_name' => 'link',
                'name' => $this->request->getPost('name'),
                'url' => $this->request->getPost('url'),
                'new_blank' => $this->request->getPost('static_new_blank') ? $this->request->getPost('static_new_blank') : 'N',
                'active' => 'Y',
                'sort' => $sort
            ];

            $menu_item = new MenuItem();
            $menu_item->assign($data);
            if ($menu_item->save()) {
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $langId = $tmp->language->id;
                        $langCode = $tmp->language->code;
                        $menuLang = Menu::findFirst([
                            "conditions" => "language_id = $langId AND depend_id = $id"
                        ]);
                        if ($menuLang) {
                            $menuItemVi = $menu_item->toArray();
                            $menuItemVi['language_id'] = $langId;
                            $menuItemVi['menu_id'] = $menuLang->id;
                            $menuItemVi['depend_id'] = $menuItemVi['id'];
                            unset($menuItemVi['id']);
                            unset($menuItemVi['created_at']);
                            unset($menuItemVi['modified_in']);
                            $menuItemLang = new MenuItem();
                            $menuItemLang->assign($menuItemVi);
                            if (!$menuItemLang->save()) {
                                foreach ($menuItemLang->getMessages() as $message) {
                                    $this->flashSession->error($message);
                                }
                            }
                        }
                    }
                }
            }

            // update elastic
            $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'menu_item']);
            
            $this->flashSession->success('Thêm menu thành công');
            $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;

            $this->response->redirect($url);
        }
    }

    public function showAction(int $id, int $page = 1)
    {
        $item = Menu::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'Y',
        ]);

        if ($item->save()) {
            
            $module_item = ModuleItem::findFirstById($item->module_item_id);
            $module_item->assign([
                'active' => 'Y',
            ]);
            $module_item->save();
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideAction(int $id, int $page = 1)
    {
        $item = Menu::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        ;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'N'
        ]);

        if ($item->save()) {
            
            $module_item = ModuleItem::findFirstById($item->module_item_id);
            $module_item->assign([
                'active' => 'N',
            ]);
            $module_item->save();
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function showMainAction(int $id, int $page = 1)
    {
        $item = Menu::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'main' => 'Y',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideMainAction(int $id, int $page = 1)
    {
        $item = Menu::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        ;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'main' => 'N'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function showmultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Menu::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'Y'
                ]);
                if ($item->save()) {
                    
                    $module_item = ModuleItem::findFirstById($item->module_item_id);
                    $module_item->assign([
                        'active' => 'Y',
                    ]);
                    $module_item->save();
                }
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if ($d > 0) {
            $this->flashSession->success($this->_message["show"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function hidemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Menu::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'N'
                ]);
                if ($item->save()) {
                    $module_item = ModuleItem::findFirstById($item->module_item_id);
                    $module_item->assign([
                        'active' => 'N',
                    ]);
                    $module_item->save();
                }
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if ($d > 0) {
            
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function deleteAction(int $id, $page = 1)
    {
        $item = Menu::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $count_menu_item = $this->count_menu_item($id);
        if ($count_menu_item > 0) {
            $this->flash->error("Không thể xóa vì chứa ràng buộc dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        $item->assign([
            'deleted' => 'Y'
        ]);

        if ($item->save()) {
            
            $module_item = ModuleItem::findFirstById($item->module_item_id);
            $module_item->assign([
                'deleted' => 'Y',
            ]);
            $module_item->save();
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error($item->getMessages());
        }


        $this->response->redirect($url);
    }

    public function deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        foreach ($listid as $id) {
            $item = Menu::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);

            $count_menu_item = $this->count_menu_item($id);
            if ($count_menu_item > 0) {
                $this->flash->error("Không thể xóa vì chứa ràng buộc dữ liệu");
                return $this->dispatcher->forward(array('action' => 'index'));
            }

            if ($item) {
                $item->assign([
                    'deleted' => 'N'
                ]);
                if ($item->save()) {
                    $module_item = ModuleItem::findFirstById($item->module_item_id);
                    $module_item->assign([
                        'deleted' => 'Y',
                    ]);
                    $module_item->save();
                }
                $d++;
            }
        }


        if ($d > 0) {
            
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function _deleteAction(int $id, $page = 1)
    {
        $item = Menu::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            
            $module_item = ModuleItem::findFirstById($item->module_item_id);
            $module_item->delete();
            TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $item->module_item_id .'');
            $this->flashSession->success($this->_message["delete"]);
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function _deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Menu::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                if ($item->delete()) {
                    $module_item = ModuleItem::findFirstById($item->module_item_id);
                    $module_item->delete();
                    TmpPositionModuleItem::deleteByRawSql('module_item_id ='. $item->module_item_id .'');
                }
                $d++;
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if ($d > 0) {
            
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function count_menu_item(int $id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['b' => 'Modules\Models\MenuItem'])
            ->where('b.menu_id = '. $id .' AND b.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }

    public function recursive_menu($menu_id, $parent_id = 0, $space = "", $trees = array())
    {
        if (!$trees) {
            $trees = [];
        }
        $result = MenuItem::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "menu_id = $menu_id AND parent_id = $parent_id AND subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
            ]
        );


        $trees_obj = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $trees[] = [
                    'id' => $row->id,
                    'module_name' => $row->module_name,
                    'module_id' => $row->id,
                    'parent_id' => $row->parent_id,
                    'level' => $row->level,
                    'name' => $row->name,
                    'space_name' => $space . $row->name,
                    'url' => $row->url,
                    'sort' => $row->sort,
                    'new_blank' => $row->new_blank,
                    'active' => $row->active,
                    'font_class' => $row->font_class,
                    'photo' => $row->photo,
                    'icon_type' => $row->icon_type,
                ];
                $trees   = $this->recursive_menu($menu_id, $row->id, $space . '|---', $trees);
            }
        }

        if (!empty($trees)) {
            foreach ($trees as $tree) {
                $tree        = (object) $tree;
                $trees_obj[] = $tree;
            }
        }
        return $trees_obj;
    }

    public function recursive($parent_id = 0, $space = "", $trees = array())
    {
        if (!$trees) {
            $trees = [];
        }
        $result = Category::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "parent_id = ". $parent_id ." AND subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
            ]
        );


        $trees_obj = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $trees[] = [
                    'module_name' => 'category',
                    'module_id' => $row->id,
                    'parent_id' => $row->parent_id,
                    'level' => $row->level,
                    'name' => $row->name,
                    'space_name' => $space . $row->name,
                    'url' => $row->slug,
                    'sort' => $row->sort,
                ];
                $trees   = $this->recursive($row->id, $space . '|---', $trees);
            }
        }

        if (!empty($trees)) {
            foreach ($trees as $tree) {
                $trees_obj[] = $tree;
            }
        }
        return $trees_obj;
    }
}
