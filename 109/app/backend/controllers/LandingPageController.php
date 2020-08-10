<?php namespace Modules\Backend\Controllers;

use Modules\Models\LandingPage;
use Modules\Models\Menu;
use Modules\Models\MenuItem;
use Modules\Models\TmpNewsLandingPage;
use Modules\Models\Setting;
use Modules\Models\LayoutConfig;
use Modules\Models\BannerType;
use Modules\Models\Posts;
use Modules\Models\ModuleGroup;
use Modules\Models\ModuleItem;
use Modules\Models\TmpLandingModule;
use Modules\Forms\LandingPageForm;
use Modules\PhalconVn\General;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Security\Random;

class LandingPageController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Landing Page';
        $this->_message = $this->getMessage();
    }

    /**
     * display list landing page
     * 
     * @return View|Phalcon\Http\Response
     */
    public function indexAction()
    {
        $url_page = ACP_NAME . '/' . $this->_getControllerName();
        $query = LandingPage::query();
        $conditions = "Modules\Models\LandingPage.subdomain_id = ". $this->_get_subdomainID() ." AND Modules\Models\LandingPage.language_id = 1 AND deleted = 'N'";
        $orderBy = "sort ASC, id DESC";

        $landingPages = $query->where($conditions)->orderBy($orderBy)->execute();
        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            array(
                "data" => $landingPages,
                "limit" => 50,
                "page" => $numberPage
            )
        );

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        if ($this->request->isPost()) {
            foreach ($paginator->getPaginate()->items as $landingPage) {
                $id = $landingPage->id;
                //save active
                $activeValue = $this->request->getPost('active_' . $landingPage->id);
                if (!empty($activeValue)) {
                    $landingPage->active = 'Y';
                } else {
                    $landingPage->active = 'N';
                }

                //save sort
                $sortValue = $this->request->getPost('sort_' . $landingPage->id);
                if (!empty($sortValue)) {
                    $landingPage->sort = $sortValue;
                } else {
                    $landingPage->sort = 1;
                }

                //save menu
                $menuItemIdArr = [];
                $menuValue = $this->request->getPost('menu_' . $landingPage->id);
                if (!empty($menuValue)) {
                    $landingPage->menu = 'Y';
                    $menu = Menu::find([
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND active = 'Y' AND main = 'Y'"
                    ]);
                    if (count($menu) > 0) {
                        foreach ($menu as $row) {
                            $menu_item_current = MenuItem::findFirst([
                                "conditions" => "menu_id = ". $row->id ." AND module_id = $id AND active = 'Y' AND module_name = 'landing_page'",
                                "order" => "id DESC"
                            ]);

                            if (!$menu_item_current) {
                                if (count($row->menu_item) > 0) {
                                    $menu_item_category = MenuItem::findFirst([
                                        "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page'",
                                        "order" => "sort DESC"
                                    ]);
                                    
                                    $sort = ($menu_item_category) ? $menu_item_category->sort + 1 : 3;
                                } else {
                                    $sort = 1;
                                }
                                
                                $menuItem = new MenuItem();
                                $menuItem->assign([
                                    'subdomain_id' => $this->_get_subdomainID(),
                                    'menu_id' => $row->id,
                                    'parent_id' => 0,
                                    'language_id' => 1,
                                    'module_id' => $id,
                                    'level' => 0,
                                    'module_name' => 'landing_page',
                                    'name' => $landingPage->name,
                                    'url' => $landingPage->slug,
                                    'active' => 'Y',
                                    'sort' => $sort
                                ]);

                                if ($menuItem->save()) {
                                    $menuItemIdArr[] = $menuItem->id;
                                }
                            }
                        }
                    } else {
                        $this->flashSession->error("Hiện tại bạn chưa tạo main menu nào!");
                    }
                } else {
                    $landingPage->menu = 'N';
                    $menu = Menu::find([
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND active = 'Y' AND main = 'Y'"
                    ]);
                    if (count($menu) > 0) {
                        foreach ($menu as $row) {
                            $menuItem = MenuItem::findFirst([
                                "conditions" => "menu_id = " . $row->id . " AND module_name = 'landing_page' AND module_id = $id"
                            ]);
                            if ($menuItem) {
                                $menuItem->delete();
                            }
                        }
                    }
                }
                
                $landingPage->save();

                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $langId = $tmp->language->id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $landingPageLang = LandingPage::findFirst([
                                'conditions' => 'depend_id = '. $landingPage->id .' AND language_id = '. $langId .''
                            ]);

                            if ($landingPageLang) {
                                $landingPageLangId = $categoryLang->id;
                                //save active
                                $activeValue = $this->request->getPost('active_' . $landingPage->id);
                                if (!empty($activeValue)) {
                                    $landingPageLang->active = 'Y';
                                } else {
                                    $landingPageLang->active = 'N';
                                }

                                //save sort
                                $sortValue = $this->request->getPost('sort_' . $landingPage->id);
                                if (!empty($sortValue)) {
                                    $landingPageLang->sort = $sortValue;
                                } else {
                                    $landingPageLang->sort = 1;
                                }

                                //save menu
                                $menuValue = $this->request->getPost('menu_' . $landingPage->id);
                                if (!empty($menuValue)) {
                                    $landingPage->menu = 'Y';
                                    $menu = Menu::find([
                                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND active = 'Y' AND main = 'Y'"
                                    ]);
                                    if (count($menu) > 0) {
                                        foreach ($menu as $keyMenu => $row) {
                                            $menu_item_current = MenuItem::findFirst([
                                                "conditions" => "menu_id = ". $row->id ." AND module_id = $landingPageLangId AND active = 'Y' AND module_name = 'landing_page'",
                                                "order" => "id DESC"
                                            ]);

                                            if (!$menu_item_current) {
                                                if (count($row->menu_item) > 0) {
                                                    $menu_item_category = MenuItem::findFirst([
                                                        "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page'",
                                                        "order" => "sort DESC"
                                                    ]);
                                                    
                                                    $sort = ($menu_item_category) ? $menu_item_category->sort + 1 : 3;
                                                } else {
                                                    $sort = 1;
                                                }
                                                
                                                $menuItemLang = new MenuItem();
                                                $menuItemLang->assign([
                                                    'subdomain_id' => $this->_get_subdomainID(),
                                                    'menu_id' => $row->id,
                                                    'language_id' => $langId,
                                                    'parent_id' => 0,
                                                    'module_id' => $landingPageLangId,
                                                    'depend_id' => $menuItemIdArr[$keyMenu],
                                                    'level' => 0,
                                                    'module_name' => 'landing_page',
                                                    'name' => $landingPageLang->name,
                                                    'url' => $landingPageLang->slug,
                                                    'active' => 'Y',
                                                    'sort' => $sort
                                                ]);

                                                $menuItemLang->save();
                                            }
                                        }
                                    }
                                } else {
                                    $landingPageLang->menu = 'N';
                                    $menu = Menu::find([
                                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
                                    ]);
                                    if (count($menu) > 0) {
                                        foreach ($menu as $row) {
                                            $menuItem = MenuItem::findFirst([
                                                "conditions" => "menu_id = " . $row->id . " AND language_id = $langId AND module_name = 'landing_page' AND module_id = $landingPageLangId"
                                            ]);
                                            if ($menuItem) {
                                                $menuItem->delete();
                                            }
                                        }
                                    }
                                }

                                $landingPageLang->save();
                            }
                        }
                    }
                }

                
            }
            $this->flashSession->success($this->_message["edit"]);
            $url = ($numberPage > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $numberPage :  ACP_NAME . '/' . $this->_getControllerName();
            $this->response->redirect($url);
        }

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
        $this->view->url_page = $url_page;
    }

    /**
     * create new landing page
     * 
     * @return View|Phalcon\Http\Response
     */
    public function createAction()
    {
        $random = new Random();
        if ($this->cookies->has('row_id_landing_page_' . $this->_get_subdomainID())) {
            // Get the cookie
            $rowIdCookie = $this->cookies->get('row_id_landing_page_' . $this->_get_subdomainID());

            // Get the cookie's value
            $row_id = $rowIdCookie->getValue();
        } else {
            $row_id = $random->hex(10);
            $this->cookies->set(
                'row_id_landing_page_' . $this->_get_subdomainID(),
                $row_id,
                time() + ROW_ID_COOKIE_TIME
            );
        }

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/landing_page/'. $row_id;
        $dir = DOCUMENT_ROOT . '/public/' . $folderImg;
        $imgUploadPaths = [];
        if (is_dir($dir)) {
            $imgUploads = array_filter(scandir($dir), function ($item) {
                return $item[0] !== '.';
            });

            if (!empty($imgUploads)) {
                foreach ($imgUploads as $img) {
                    if ($img != 'medium') {
                        $imgUploadPaths[] = '/' . $folderImg . '/' . $img;
                    }
                }
            }
        }

        if (count($this->_tmpSubdomainLanguages) > 0) {
            $row_id_lang = [];
            $imgUploadLangPaths = [];
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $random = new Random();
                    if ($this->cookies->has('row_id_landing_page_' . $langCode . '_' . $this->_get_subdomainID())) {
                        // Get the cookie
                        $rowIdCookie = $this->cookies->get('row_id_landing_page_' . $langCode . '_' . $this->_get_subdomainID());

                        // Get the cookie's value
                        $row_id_cookie = $rowIdCookie->getValue();
                    } else {
                        $row_id_cookie = $random->hex(10);
                        $this->cookies->set(
                            'row_id_landing_page_' . $langCode . '_' . $this->_get_subdomainID(),
                            $row_id_cookie,
                            time() + ROW_ID_COOKIE_TIME
                        );
                    }

                    $row_id_lang[$langCode] = $row_id_cookie;

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/landing_page/'. $row_id_lang[$langCode];
                    $dir = DOCUMENT_ROOT . '/public/' . $folderImg;
                    $imgUploadLangPaths[$langCode] = [];
                    if (is_dir($dir)) {
                        $imgUploads = array_filter(scandir($dir), function ($item) {
                            return ($item[0] !== '.');
                        });

                        if (!empty($imgUploads)) {
                            foreach ($imgUploads as $img) {
                                if ($img != 'medium') {
                                    $imgUploadLangPaths[$langCode][] = '/' . $folderImg . '/' . $img;
                                }
                            }
                        }
                    }
                }
            }

            $this->view->row_id_lang = $row_id_lang;
            $this->view->img_upload_lang_paths = $imgUploadLangPaths;
        }

        $moduleElements = [];
        $moduleItems = $this->modelsManager->createBuilder()
            ->columns(
                "mi.module_group_id,
                mi.parent_id,
                mi.name AS module_name,
                mi.id AS module_id,
                mi.module_group_id,
                mi.sort AS module_sort,
                mi.type AS module_type,
                tmp.id,
                tmp.landing_page_id,
                tmp.active,
                tmp.sort"
            )
            ->addFrom("Modules\Models\ModuleItem", "mi")
            ->leftJoin("Modules\Models\TmpLandingModule", "mi.id = tmp.module_item_id", "tmp")
            ->where("mi.subdomain_id = ". $this->_get_subdomainID() ." AND parent_id = 0")
            ->orderBy("tmp.sort ASC, tmp.id DESC, mi.name ASC, mi.sort ASC, mi.type ASC, mi.id DESC")
            ->getQuery()
            ->execute();

        foreach ($moduleItems as $key => $moduleItem) {
            switch ($moduleItem->module_type) {
                case 'banner':
                    $bannerType = BannerType::findFirstByModuleItemId($moduleItem->module_id);
                    $url = ACP_NAME . '/banner';
                    break;
                case 'post':
                    $post = Posts::findFirstByModuleItemId($moduleItem->module_id);
                    $url = ACP_NAME . '/posts';
                    break;
                case 'menu':
                    $menu = Menu::findFirstByModuleItemId($moduleItem->module_id);
                    if ($menu) {
                        $url = ACP_NAME . '/menu/update/' . $menu->id;
                    }
                    break;
                
                default:
                    $moduleGroup = ModuleGroup::findFirstById($moduleItem->module_group_id);
                    if ($moduleGroup && !empty($moduleGroup->link) != '') {
                        $url = ACP_NAME . '/' . $moduleGroup->link;
                    } else {
                        $url = '';
                    }
                      
                    break;
            }

            $itemModule = $moduleItem->toArray();
            $itemModule['url'] = $url;
            $itemModule['module_name'] = ($moduleItem->module_type == 'post') ? 'Tự soạn thảo: ' . $moduleItem->module_name : $moduleItem->module_name;

            // get child
            if ($moduleItem->module_type != 'banner' && $moduleItem->module_type != 'post' && $moduleItem->module_type != 'menu') {
                $moduleItemChilds = $this->modelsManager->createBuilder()
                    ->columns(
                        "mi.module_group_id,
                        mi.parent_id,
                        mi.name AS module_name,
                        mi.id AS module_id,
                        mi.module_group_id,
                        mi.sort AS module_sort,
                        mi.type AS module_type,
                        mi.active AS module_active,
                        tmp.id,
                        tmp.landing_page_id,
                        tmp.active,
                        tmp.sort"
                    )
                    ->addFrom("Modules\Models\ModuleItem", "mi")
                    ->leftJoin("Modules\Models\TmpLandingModule", "mi.id = tmp.module_item_id", "tmp")
                    ->where("mi.subdomain_id = ". $this->_get_subdomainID() ." AND parent_id = ". $moduleItem->module_id ."")
                    ->orderBy("tmp.sort ASC, tmp.id DESC, mi.sort ASC, mi.name ASC, mi.type ASC, mi.id DESC")
                    ->getQuery()
                    ->execute();

                if (count($moduleItemChilds) == 0) {
                    $moduleItemChilds = ModuleItem::find([
                        'columns' => 'id AS module_id, parent_id, name AS module_name, level, type, sort, active',
                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND parent_id = '. $moduleItem->module_id .''
                    ]);
                }

                if (count($moduleItemChilds) > 0) {
                    $itemModule['child'] = $moduleItemChilds->toArray();
                }
            }
           
            $moduleElements[] = $itemModule;
        }

        /*uasort($moduleElements, function($a, $b){
            if ($a['sort'] == "") return 1;
            if ($b['sort'] == "") return -1;
            return $a['sort'] - $b['sort'];
        });*/

        $moduleElements = array_values($moduleElements);

        $form = new LandingPageForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new LandingPage();
            $general = new General();
            $menuActive = ($this->request->getPost('menu') == 'Y') ? 'Y' : 'N';

            $slug = $this->mainGlobal->validateUrlPageCreate($general->create_slug($this->request->getPost('name'))) ? $general->create_slug($this->request->getPost('name')) : $general->create_slug($this->request->getPost('name')) . '-' . mt_rand(100, 999);
            
            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'sort' => $this->request->getPost('sort'),
                'menu' => $menuActive,
                'active' => ($this->request->getPost('active') == 'Y') ? 'Y' : 'N',
                'icon_type' => $this->request->getPost('icon_type'),
                'font_class' => $this->request->getPost('font_class'),
                'hide_header' => $this->request->getPost('hide_header'),
                'hide_left' => $this->request->getPost('hide_left'),
                'hide_right' => $this->request->getPost('hide_right'),
                'hide_footer' => $this->request->getPost('hide_footer'),
            ];

            $subFolder = $this->_get_subdomainFolder();
            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    if (!empty($file->getName())) {
                        // upload for icon
                        if ($file->getKey() == 'icon') {
                            $subfolderUrl = 'files/icon/' . $subFolder;
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl, null, 'category');
                            if (!empty($dataUpload['file_name'])) {
                                $data['icon'] = $dataUpload['file_name'];
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }
                    }
                }
            }

            $item->assign($data);

            if ($item->save()) {
                $id = $item->id;

                //save other language
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $data = [];
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            if ($this->request->getPost('parent_id') != 0) {
                                $parentLang = LandingPage::findFirst([
                                    'conditions' => 'depend_id = '. $this->request->getPost('parent_id') .' AND language_id = '. $langId .' AND subdomain_id = '. $this->_get_subdomainID() .''
                                ]);

                                $parent_id = $parentLang ? $parentLang->id : 0;
                            } else {
                                $parent_id = 0;
                            }
                            
                            $slug = $this->mainGlobal->validateUrlPageCreate($general->create_slug($this->request->getPost('name_' . $langCode))) ? $general->create_slug($this->request->getPost('name_' . $langCode)) : $general->create_slug($this->request->getPost('name_' . $langCode)) . '-' . mt_rand(100, 999);
                            $data = [
                                'subdomain_id' => $this->_get_subdomainID(),
                                'language_id' => $langId,
                                'depend_id' => $id,
                                'sort' => $this->request->getPost('sort'),
                                'menu' => ($this->request->getPost('menu') == 'Y') ? 'Y' : 'N',
                                'active' => ($this->request->getPost('active') == 'Y') ? 'Y' : 'N',
                                'icon_type' => $this->request->getPost('icon_type'),
                                'font_class' => $this->request->getPost('font_class'),
                                'name' => $this->request->getPost('name_' . $langCode),
                                'slug' => $slug,
                                'title' => $this->request->getPost('title_' . $langCode),
                                'keywords' => $this->request->getPost('keywords_' . $langCode),
                                'description' => $this->request->getPost('description_' . $langCode),
                                'hide_header' => $item->hide_header,
                                'hide_left' => $item->hide_left,
                                'hide_right' => $item->hide_right,
                                'hide_footer' => $item->hide_footer,
                                'icon' => $item->icon
                            ];

                            $landingPageLang = new LandingPage();
                            $landingPageLang->assign($data);
                            $landingPageLang->save();
                        }
                    }
                }

                if ($this->request->getPost('active_module')) {
                    $activeModules = $this->request->getPost('active_module');
                    $sortModules = $this->request->getPost('sort_module');
                    foreach ($activeModules as $key => $activeModule) {
                        $moduleItem = ModuleItem::findFirstById($key);
                        if ($moduleItem->parent_id == 0) {
                            $tmpLandingModule = new TmpLandingModule();
                            $tmpLandingModule->assign([
                                'subdomain_id' => $this->_get_subdomainID(),
                                'landing_page_id' => $id,
                                'module_item_id' => $key,
                                'active' => 'Y',
                                'sort' => $sortModules[$key],
                            ]);

                            $tmpLandingModule->save();
                        } else {
                            $moduleItemParentId = $moduleItem->parent_id;
                            if (isset($activeModules[$moduleItemParentId])) {
                                $tmpLandingModule = new TmpLandingModule();
                                $tmpLandingModule->assign([
                                    'subdomain_id' => $this->_get_subdomainID(),
                                    'landing_page_id' => $id,
                                    'module_item_id' => $key,
                                    'active' => 'Y',
                                    'sort' => $sortModules[$key],
                                ]);

                                $tmpLandingModule->save();
                            }
                        }
                    }
                }

                
                $this->cookies->get('row_id_landing_page_' . $this->_get_subdomainID())->delete();
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langCode = $tmp->language->code;
                    $this->cookies->get('row_id_landing_page_' . $langCode . '_' . $this->_get_subdomainID())->delete();
                }

                $this->flashSession->success($this->_message["add"]);
                // if ($item->menu != 'Y') $this->flashSession->warning("Bạn chưa click chọn menu top!");

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/index';
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;
                }

                $this->response->redirect($url);
            }
        }

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $setting = Setting::findFirst(array(
            'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .''
        ));

        $layoutId = $setting->layout_id;

        $layoutConfig = LayoutConfig::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND layout_id = $layoutId"
        ]);

        $this->view->layout_config = $layoutConfig;
        $this->view->moduleElements = $moduleElements;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->row_id = $row_id;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    /**
     * Saves the landing page from the 'update' action
     * @param  integer  $id   
     * @param  integer $page 
     * 
     * @return View|Phalcon\Http\Response
     */
    public function updateAction($id, $page = 1)
    {
        $item = LandingPage::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (count($this->_tmpSubdomainLanguages) > 0) {
            $itemFormData = $item->toArray();
            $row_id_lang = [];
            $imgUploadLangPaths = [];
            $itemLangData = [];
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $itemLang = LandingPage::findFirst(array(
                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $tmp->language_id .' AND depend_id = '. $id .''
                    ));

                    if ($itemLang) {
                        $row_id_lang[$langCode] = $itemLang->row_id;
                        $itemLangData[$langCode] = $itemLang;
                        $itemLang = $itemLang->toArray();
                        $itemLangKeys = array_keys($itemLang);
                        foreach ($itemLangKeys as $itemLangKey) {
                            $itemFormData[$itemLangKey . '_' . $langCode] = $itemLang[$itemLangKey];
                        }
                    } else {
                        $random = new Random();
                        if ($this->cookies->has('row_id_landing_page_' . $langCode . '_' . $this->_get_subdomainID())) {
                            // Get the cookie
                            $rowIdCookie = $this->cookies->get('row_id_landing_page_' . $langCode . '_' . $this->_get_subdomainID());

                            // Get the cookie's value
                            $row_id_cookie = $rowIdCookie->getValue();
                        } else {
                            $row_id_cookie = $random->hex(10);
                            $this->cookies->set(
                                'row_id_landing_page_' . $langCode . '_' . $this->_get_subdomainID(),
                                $row_id_cookie,
                                time() + ROW_ID_COOKIE_TIME
                            );
                        }

                        $row_id_lang[$langCode] = $row_id_cookie;
                    }

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/landing_page/'. $row_id_lang[$langCode];
                    $dir = DOCUMENT_ROOT . '/public/' . $folderImg;
                    $imgUploadLangPaths[$langCode] = [];
                    if (is_dir($dir)) {
                        $imgUploads = array_filter(scandir($dir), function ($item) {
                            return ($item[0] !== '.');
                        });

                        if (!empty($imgUploads)) {
                            foreach ($imgUploads as $img) {
                                if ($img != 'medium') {
                                    $imgUploadLangPaths[$langCode][] = '/' . $folderImg . '/' . $img;
                                }
                            }
                        }
                    }
                }
            }

            $itemFormData = (object) $itemFormData;
            $this->view->row_id_lang = $row_id_lang;
            $this->view->img_upload_lang_paths = $imgUploadLangPaths;
        } else {
            $itemFormData = $item;
        }

        $icon = $item->icon;
        $row_id = ($item->row_id != 0) ? $item->row_id : $item->id;

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/landing_page/'. $row_id;
        $dir = DOCUMENT_ROOT . '/public/' . $folderImg;

        $imgUploadPaths = [];
        if (is_dir($dir)) {
            $imgUploads = array_filter(scandir($dir), function ($item) {
                return $item[0] !== '.';
            });

            if (!empty($imgUploads)) {
                foreach ($imgUploads as $img) {
                    if ($img != 'medium') {
                        $imgUploadPaths[] = '/' . $folderImg . '/' . $img;
                    }
                }
            }
        }

        $moduleElements = [];
        $moduleItems = $this->modelsManager->createBuilder()
            ->columns(
                "mi.module_group_id,
                mi.parent_id,
                mi.name AS module_name,
                mi.id AS module_id,
                mi.module_group_id,
                mi.sort AS module_sort,
                mi.type AS module_type,
                tmp.id,
                tmp.landing_page_id,
                tmp.active,
                tmp.sort"
            )
            ->addFrom("Modules\Models\ModuleItem", "mi")
            ->leftJoin("Modules\Models\TmpLandingModule", "mi.id = tmp.module_item_id", "tmp")
            ->where("mi.subdomain_id = ". $this->_get_subdomainID() ." AND parent_id = 0")
            ->orderBy("tmp.sort ASC, tmp.landing_page_id DESC, tmp.subdomain_id DESC, tmp.id DESC, mi.name ASC, mi.sort ASC, mi.type ASC, mi.id DESC")
            ->getQuery()
            ->execute();

        foreach ($moduleItems as $key => $moduleItem) {
            switch ($moduleItem->module_type) {
                case 'banner':
                    $bannerType = BannerType::findFirstByModuleItemId($moduleItem->module_id);
                    $url = ACP_NAME . '/banner';
                    break;
                case 'post':
                    $post = Posts::findFirstByModuleItemId($moduleItem->module_id);
                    $url = ACP_NAME . '/posts';
                    break;
                case 'menu':
                    $menu = Menu::findFirstByModuleItemId($moduleItem->module_id);
                    if ($menu) {
                        $url = ACP_NAME . '/menu/update/' . $menu->id;
                    }
                    break;
                
                default:
                    $moduleGroup = ModuleGroup::findFirstById($moduleItem->module_group_id);
                    if ($moduleGroup && !empty($moduleGroup->link) != '') {
                        $url = ACP_NAME . '/' . $moduleGroup->link;
                    } else {
                        $url = '';
                    }
                      
                    break;
            }

            $itemModule = $moduleItem->toArray();
            $itemModule['url'] = $url;
            $itemModule['module_name'] = ($moduleItem->module_type == 'post') ? 'Tự soạn thảo: ' . $moduleItem->module_name : $moduleItem->module_name;

            // get child
            if ($moduleItem->module_type != 'banner' && $moduleItem->module_type != 'post' && $moduleItem->module_type != 'menu') {
                $moduleItemChilds = $this->modelsManager->createBuilder()
                    ->columns(
                        "mi.module_group_id,
                        mi.parent_id,
                        mi.name AS module_name,
                        mi.id AS module_id,
                        mi.module_group_id,
                        mi.sort AS module_sort,
                        mi.type AS module_type,
                        mi.active AS module_active,
                        tmp.id,
                        tmp.landing_page_id,
                        tmp.active,
                        tmp.sort"
                    )
                    ->addFrom("Modules\Models\ModuleItem", "mi")
                    ->leftJoin("Modules\Models\TmpLandingModule", "mi.id = tmp.module_item_id", "tmp")
                    ->where("mi.subdomain_id = ". $this->_get_subdomainID() ." AND parent_id = ". $moduleItem->module_id ."")
                    ->orderBy("tmp.sort ASC, tmp.id DESC, mi.sort ASC, mi.name ASC, mi.type ASC, mi.id DESC")
                    ->getQuery()
                    ->execute();

                if (count($moduleItemChilds) == 0) {
                    $moduleItemChilds = ModuleItem::find([
                        'columns' => 'id AS module_id, parent_id, name AS module_name, level, type, sort, active',
                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND parent_id = '. $moduleItem->module_id .''
                    ]);
                }

                if (count($moduleItemChilds) > 0) {
                    $itemModule['child'] = $moduleItemChilds->toArray();
                }
            }
           
            $moduleElements[] = $itemModule;
        }

        uasort($moduleElements, function ($a, $b) {
            if ($a['sort'] == "") {
                return 1;
            }
            if ($b['sort'] == "") {
                return -1;
            }
            return $a['sort'] - $b['sort'];
        });

        $moduleElements = array_values($moduleElements);

        $form = new LandingPageForm($itemFormData, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $general = new General();
            $slug = $this->mainGlobal->validateUrlPageUpdate($id, $general->create_slug($this->request->getPost('name')), 'landing_page') ? $general->create_slug($this->request->getPost('name')) : $general->create_slug($this->request->getPost('name')) . '-' . mt_rand(100, 999);

            $data = [
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'sort' => $this->request->getPost('sort'),
                'active' => ($this->request->getPost('active') == 'Y') ? 'Y' : 'N',
                'row_id' => $this->request->getPost('row_id'),
                'icon_type' => $this->request->getPost('icon_type'),
                'font_class' => $this->request->getPost('font_class'),
                'menu' => ($this->request->getPost('menu') == 'Y') ? 'Y' : 'N',
                'hide_header' => $this->request->getPost('hide_header'),
                'hide_left' => $this->request->getPost('hide_left'),
                'hide_right' => $this->request->getPost('hide_right'),
                'hide_footer' => $this->request->getPost('hide_footer'),
            ];

            $subFolder = $this->_get_subdomainFolder();
            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    if (!empty($file->getName())) {
                        // upload for icon
                        if ($file->getKey() == 'icon') {
                            $subfolderUrl = 'files/icon/' . $subFolder;
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl, null, 'category');
                            if (!empty($dataUpload['file_name'])) {
                                $data['icon'] = $dataUpload['file_name'];
                                @unlink($subfolderUrl . '/' . $icon);
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }
                    }
                }
            }

            $item->assign($data);

            if ($item->save()) {
                $menu = Menu::find([
                    "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND active = 'Y' AND main = 'Y'"
                ]);

                if (count($menu) > 0) {
                    foreach ($menu as $row) {
                        $menuItem = MenuItem::findFirst([
                            "conditions" => "menu_id = ". $row->id ." AND module_id = $id AND active = 'Y' AND module_name = 'landing_page'",
                        ]);
                        if ($menuItem) {
                            $menuItem->assign([
                                'name' => $this->request->getPost('name'),
                                'url' => $slug
                            ]);

                            $menuItem->save();
                        }
                    }
                }

                //save other language
                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $data = [];
                        $langId = $tmp->language_id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $slug = $this->mainGlobal->validateUrlPageCreate($general->create_slug($this->request->getPost('name_' . $langCode)), $langId) ? $general->create_slug($this->request->getPost('name_' . $langCode)) : $general->create_slug($this->request->getPost('name_' . $langCode)) . '-' . mt_rand(100, 999);
                            $data = [
                                'subdomain_id' => $this->_get_subdomainID(),
                                'language_id' => $langId,
                                'depend_id' => $id,
                                'sort' => $this->request->getPost('sort'),
                                'menu' => ($this->request->getPost('menu') == 'Y') ? 'Y' : 'N',
                                'active' => ($this->request->getPost('active') == 'Y') ? 'Y' : 'N',
                                'icon_type' => $this->request->getPost('icon_type'),
                                'font_class' => $this->request->getPost('font_class'),
                                'name' => $this->request->getPost('name_' . $langCode),
                                'slug' => $slug,
                                'title' => $this->request->getPost('title_' . $langCode),
                                'keywords' => $this->request->getPost('keywords_' . $langCode),
                                'description' => $this->request->getPost('description_' . $langCode),
                                'icon' => $item->icon,
                                'hide_header' => $item->hide_header,
                                'hide_left' => $item->hide_left,
                                'hide_right' => $item->hide_right,
                                'hide_footer' => $item->hide_footer,
                            ];


                            $landingPageLang = LandingPage::findFirst(array(
                                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .' AND depend_id = '. $id .''
                            ));
                            if (!$landingPageLang) {
                                $landingPageLang = new LandingPage();
                            } else {
                                // update depend_id for childs
                                $landingPageChilds = LandingPage::findByParentId($id);
                                if (count($landingPageChilds) > 0) {
                                    foreach ($landingPageChilds as $landingPageChild) {
                                        $landingPageChildLang = LandingPage::findFirst([
                                            'conditions' => 'depend_id = '. $landingPageChild->id .' AND language_id = '. $langId .''
                                        ]);

                                        if ($landingPageChildLang) {
                                            $landingPageChildLang->parent_id = $landingPageLang->id;
                                            $landingPageChildLang->save();
                                        }
                                    }
                                }
                            }

                            $landingPageLang->assign($data);
                            if ($landingPageLang->save()) {
                                $menu = Menu::find([
                                    "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND active = 'Y' AND main = 'Y'"
                                ]);

                                if (count($menu) > 0) {
                                    foreach ($menu as $row) {
                                        $menuItem = MenuItem::findFirst([
                                            "conditions" => "menu_id = ". $row->id ." AND module_id = $id AND active = 'Y' AND module_name = 'landing_page'",
                                        ]);
                                        if ($menuItem) {
                                            $menuItem->assign([
                                                'name' => $this->request->getPost('name_' . $langCode),
                                                'url' => $slug
                                            ]);

                                            $menuItem->save();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                TmpLandingModule::deleteByRawSql("landing_page_id = $item->id");
                if ($this->request->getPost('active_module')) {
                    $activeModules = $this->request->getPost('active_module');
                    $sortModules = $this->request->getPost('sort_module');
                    foreach ($activeModules as $key => $activeModule) {
                        $moduleItem = ModuleItem::findFirstById($key);
                        if ($moduleItem->parent_id == 0) {
                            $tmpLandingModule = new TmpLandingModule();
                            $tmpLandingModule->assign([
                                'subdomain_id' => $this->_get_subdomainID(),
                                'landing_page_id' => $id,
                                'module_item_id' => $key,
                                'active' => 'Y',
                                'sort' => $sortModules[$key],
                            ]);

                            $tmpLandingModule->save();
                        } else {
                            $moduleItemParentId = $moduleItem->parent_id;
                            if (isset($activeModules[$moduleItemParentId])) {
                                $tmpLandingModule = new TmpLandingModule();
                                $tmpLandingModule->assign([
                                    'subdomain_id' => $this->_get_subdomainID(),
                                    'landing_page_id' => $id,
                                    'module_item_id' => $key,
                                    'active' => 'Y',
                                    'sort' => $sortModules[$key],
                                ]);

                                $tmpLandingModule->save();
                            }
                        }
                    }
                }

                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langCode = $tmp->language->code;
                    $this->cookies->get('row_id_landing_page_' . $langCode . '_' . $this->_get_subdomainID())->delete();
                }

                
                $this->flashSession->success($this->_message["edit"]);
                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id . '/' . $page;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';

        $this->view->moduleElements = $moduleElements;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->item = $item;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->row_id = $row_id;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function showAction($id, $page = 1)
    {
        $item = LandingPage::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            
            $menu = Menu::find([
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
            ]);
            if (!empty($menu)) {
                foreach ($menu as $row) {
                    $menuItem = MenuItem::findFirst([
                        "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page'",
                    ]);

                    if (!empty($menuItem)) {
                        $menuItem->assign([
                            'active' => 'Y'
                        ]);

                        $menuItem->save();
                    }
                }
            }
            $this->flashSession->success($this->_message["show"]);
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '/index?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function hideAction($id, $page = 1)
    {
        $item = LandingPage::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            
            $menu = Menu::find([
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
            ]);
            if (!empty($menu)) {
                foreach ($menu as $row) {
                    $menuItem = MenuItem::findFirst([
                        "conditions" => "menu_id = ". $row->id ." AND active = 'N' AND module_name = 'landing_page'",
                    ]);

                    if (!empty($menuItem)) {
                        $menuItem->assign([
                            'active' => 'N'
                        ]);

                        $menuItem->save();
                    }
                }
            }
            $this->flashSession->success($this->_message["hide"]);
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '/index?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function showmultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = LandingPage::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'Y',
                ));
                if ($item->save()) {
                    
                    $menu = Menu::find([
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
                    ]);
                    if (!empty($menu)) {
                        foreach ($menu as $row) {
                            $menuItem = MenuItem::findFirst([
                                "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page'",
                            ]);

                            if (!empty($menuItem)) {
                                $menuItem->assign([
                                    'active' => 'Y'
                                ]);

                                $menuItem->save();
                            }
                        }
                    }
                }
                $d++;
            }
        }

        if ($d > 0) {
            $this->flashSession->success($this->_message["show"]);
        } else {
            $this->flash->error("Không tìm thấy dữ liệu");
        }
        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;
        $this->response->redirect($url);
    }

    public function hidemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = LandingPage::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'N',
                ));
                if ($item->save()) {
                    
                    $menu = Menu::find([
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
                    ]);
                    if (!empty($menu)) {
                        foreach ($menu as $row) {
                            $menuItem = MenuItem::findFirst([
                                "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page'",
                            ]);

                            if (!empty($menuItem)) {
                                $menuItem->assign([
                                    'active' => 'N'
                                ]);

                                $menuItem->save();
                            }
                        }
                    }
                }
                $d++;
            }
        }

        if ($d > 0) {
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flash->error("LandingPage was not found");
        }
        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;
        $this->response->redirect($url);
    }

    public function deleteAction($id, $page = 1)
    {
        $item = LandingPage::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flashSession->success($this->_message["delete"]);
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'deleted' => 'Y',
        ));

        if ($item->save()) {
            
            $menu = Menu::find([
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
            ]);
            if (!empty($menu)) {
                foreach ($menu as $row) {
                    $menuItem = MenuItem::findFirst([
                        "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page'",
                    ]);

                    if (!empty($menuItem)) {
                        $menuItem->assign([
                            'deleted' => 'Y'
                        ]);

                        $menuItem->save();
                    }
                }
            }
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = LandingPage::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign(array(
                    'deleted' => 'Y',
                ));
                if ($item->save()) {
                    
                    $menu = Menu::find([
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
                    ]);
                    if (!empty($menu)) {
                        foreach ($menu as $row) {
                            $menuItem = MenuItem::findFirst([
                                "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page'",
                            ]);

                            if (!empty($menuItem)) {
                                $menuItem->assign([
                                    'deleted' => 'Y'
                                ]);

                                $menuItem->save();
                            }
                        }
                    }
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

    public function _deleteAction($id, $page = 1)
    {
        $item = LandingPage::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $icon = $item->icon;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        $sub_folder = $this->_get_subdomainFolder();
        $general = new General();

        if (!$item->delete()) {
            foreach ($item->getMessages() as $message) {
                $this->flashSession->error($message);
            }
        } else {
            TmpLandingModule::deleteByRawSql("landing_page_id = $id");

            //delete other lang item
            $dependLandingPages = LandingPage::findByDependId($id);
            if (count($dependLandingPages) > 0) {
                foreach ($dependLandingPages as $dependLandingPage) {
                    if ($dependLandingPage->delete()) {
                        if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/landing_page/" . $dependLandingPage->row_id)) {
                            $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/landing_page/" . $dependLandingPage->row_id);
                        }
                    }
                }
            }
            
            @unlink("files/icon/" . $sub_folder . "/" . $icon);
            $menu = Menu::find([
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
            ]);

            if (count($menu) > 0) {
                foreach ($menu as $row) {
                    $menuItems = MenuItem::find([
                        "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page' AND module_id = $id",
                    ]);

                    if (count($menuItems) > 0) {
                        foreach ($menuItems as $menuItem) {
                            $menuItem->delete();
                        }
                    }

                    //delete menu lang
                    if (count($this->_tmpSubdomainLanguages) > 0) {
                        foreach ($this->_tmpSubdomainLanguages as $tmp) {
                            $langId = $tmp->language->id;
                            $langCode = $tmp->language->code;
                            if ($langCode != 'vi') {
                                $landingPageLang = LandingPage::findFirst([
                                    'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                ]);
                                if ($landingPageLang) {
                                    $menuItems = MenuItem::find([
                                        "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page' AND module_id = $landingPageLang->id",
                                    ]);

                                    if (count($menuItems) > 0) {
                                        foreach ($menuItems as $menuItem) {
                                            $menuItem->delete();
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/landing_page/" . $item->row_id)) {
                $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/landing_page/" . $item->row_id);
            }

            
            $this->flashSession->success($this->_message["delete"]);
        }

        
        $this->response->redirect($url);
    }

    public function _deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $general = new General();
        $d = 0;
        foreach ($listid as $id) {
            $item = LandingPage::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $icon = $item->icon;
                $sub_folder = $this->_get_subdomainFolder();
                
                if (!$item->delete()) {
                    foreach ($item->getMessages() as $message) {
                        $this->flashSession->error($message);
                    }
                } else {
                    TmpLandingModule::deleteByRawSql("landing_page_id = $id");

                    //delete other lang item
                    $dependLandingPages = LandingPage::findByDependId($id);
                    if (count($dependLandingPages) > 0) {
                        foreach ($dependLandingPages as $dependLandingPage) {
                            if ($dependLandingPage->delete()) {
                                if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/landing_page/" . $dependLandingPage->row_id)) {
                                    $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/landing_page/" . $dependLandingPage->row_id);
                                }
                            }
                        }
                    }

                    @unlink("files/icon/" . $sub_folder . "/" . $icon);
                    $menu = Menu::find([
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
                    ]);

                    if (count($menu) > 0) {
                        foreach ($menu as $row) {
                            $menuItems = MenuItem::find([
                                "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page' AND module_id = $id",
                            ]);

                            if (count($menuItems) > 0) {
                                foreach ($menuItems as $menuItem) {
                                    $menuItem->delete();
                                }
                            }

                            //delete menu lang
                            if (count($this->_tmpSubdomainLanguages) > 0) {
                                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                                    $langId = $tmp->language->id;
                                    $langCode = $tmp->language->code;
                                    if ($langCode != 'vi') {
                                        $landingPageLang = LandingPage::findFirst([
                                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                        ]);
                                        if ($landingPageLang) {
                                            $menuItems = MenuItem::find([
                                                "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page' AND module_id = $landingPageLang->id",
                                            ]);

                                            if (count($menuItems) > 0) {
                                                foreach ($menuItems as $menuItem) {
                                                    $menuItem->delete();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    
                    if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/landing_page/" . $item->row_id)) {
                        $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/landing_page/" . $item->row_id);
                    }
                }

                $d++;
            }

            
        }
        //echo $d;die;
        
        if ($d > 0) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function showhotAction($id, $page = 0)
    {
        $landingPage = LandingPage::findFirstById($id);
        if (!$landingPage) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $landingPage->assign(array(
            'hot' => 'Y',
        ));

        if ($landingPage->save()) {
            
            $this->flashSession->success("Thao tác thành công!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function hidehotAction($id, $page = 0)
    {
        $landingPage = LandingPage::findFirstById($id);
        if (!$landingPage) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $landingPage->assign(array(
            'hot' => 'N',
        ));

        if ($landingPage->save()) {
            
            $this->flashSession->success("Thao tác thành công!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function showmenuAction($id, $page = 0)
    {
        $item = LandingPage::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'menu' => 'Y',
        ));

        $menu = Menu::find([
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
        ]);
        if (!empty($menu)) {
            foreach ($menu as $row) {
                if (count($row->menu_item) > 0) {
                    $menu_item_LandingPage = MenuItem::findFirst([
                        "conditions" => "menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'landing_page'",
                        "order" => "id DESC"
                    ]);
                    $sort = (!empty($menu_item_LandingPage)) ? $menu_item_LandingPage->sort + 1 : 3;
                } else {
                    $sort = 1;
                }

                $menuItem = new MenuItem();
                $menuItem->assign([
                    'subdomain_id' => $this->_get_subdomainID(),
                    'menu_id' => $row->id,
                    'parent_id' => 0,
                    'module_id' => $id,
                    'level' => 0,
                    'module_name' => 'landing_page',
                    'name' => $item->name,
                    'url' => $item->slug,
                    'active' => 'Y',
                    'sort' => $sort
                ]);

                $menuItem->save();
            }
            $item->save();
            
            $this->flashSession->success("Thao tác thành công!");
        } else {
            $this->flashSession->error("Hiện tại bạn chưa tạo main menu nào!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function hidemenuAction($id, $page = 0)
    {
        $item = LandingPage::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'menu' => 'N',
        ));

        $menu = Menu::find([
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
        ]);
        if (!empty($menu)) {
            foreach ($menu as $row) {
                $menuItem = MenuItem::findFirst([
                    "conditions" => "menu_id = " . $row->id . " AND module_id = $id AND module_name = 'landing_page'"
                ]);
                if ($menuItem) {
                    $menuItem->delete();
                }
            }
            $item->save();
            
            $this->flashSession->success("Thao tác thành công!");
        } else {
            $this->flashSession->error("Hiện tại bạn chưa tạo main menu nào!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function showFooterAction($id, $page = 0)
    {
        $item = LandingPage::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'footer' => 'Y',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
        } else {
            $this->flashSession->error("Đã xảy ra lỗi. Vui lòng thử lại!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function hideFooterAction($id, $page = 0)
    {
        $item = LandingPage::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'footer' => 'N',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flashSession->error("Đã xảy ra lỗi. Vui lòng thử lại!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function showHomeAction($id, $page = 0)
    {
        $item = LandingPage::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'home' => 'Y',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
        } else {
            $this->flashSession->error("Đã xảy ra lỗi. Vui lòng thử lại!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function hideHomeAction($id, $page = 0)
    {
        $item = LandingPage::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'home' => 'N',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flashSession->error("Đã xảy ra lỗi. Vui lòng thử lại!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    private function deleteCache()
    {
        
    }
}
