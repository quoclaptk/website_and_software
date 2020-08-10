<?php namespace Modules\Backend\Controllers;

use Modules\Models\NewsMenu;
use Modules\Models\Menu;
use Modules\Models\MenuItem;
use Modules\Models\TmpNewsNewsMenu;
use Modules\Forms\NewsMenuForm;
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

class NewsMenuController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Danh mục bài viết';
        $this->_message = $this->getMessage();
    }

    /**
     * display list category news
     * 
     * @return View|Phalcon\Http\Response
     */
    public function indexAction()
    {
        $items = $this->recursive(0);
        $page_current = 1;

        if ($this->request->isPost()) {
            foreach ($items as $item) {
                $category = NewsMenu::findFirstById($item->id);
                $id = $item->id;

                //save active
                $activeValue = $this->request->getPost('active_' . $item->id);
                if (!empty($activeValue)) {
                    $category->active = 'Y';
                } else {
                    $category->active = 'N';
                }

                //save home
                $showHomeValue = $this->request->getPost('home_' . $item->id);
                if (!empty($showHomeValue)) {
                    $category->home = 'Y';
                } else {
                    $category->home = 'N';
                }

                //save footer
                $footerValue = $this->request->getPost('footer_' . $item->id);
                if (!empty($footerValue)) {
                    $category->footer = 'Y';
                } else {
                    $category->footer = 'N';
                }

                //save policy
                $policyValue = $this->request->getPost('policy_' . $item->id);
                if (!empty($policyValue)) {
                    $category->policy = 'Y';
                } else {
                    $category->policy = 'N';
                }

                //save popup
                $popupValue = $this->request->getPost('popup_' . $item->id);
                if (!empty($popupValue)) {
                    $category->popup = 'Y';
                } else {
                    $category->popup = 'N';
                }

                //save reg_form
                $regFormValue = $this->request->getPost('reg_form_' . $item->id);
                if (!empty($regFormValue)) {
                    $category->reg_form = 'Y';
                } else {
                    $category->reg_form = 'N';
                }

                //save sort
                $sortValue = $this->request->getPost('sort_' . $item->id);
                if (!empty($sortValue)) {
                    //save sort
                    $category->sort = $sortValue;
                } else {
                    $category->sort = 1;
                }

                //save menu
                $menuItemIdArr = [];
                $menuValue = $this->request->getPost('menu_' . $item->id);
                if (!empty($menuValue)) {
                    $category->menu = 'Y';
                    $menu = Menu::find([
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND active = 'Y' AND main = 'Y'"
                    ]);
                    if (count($menu) > 0) {
                        foreach ($menu as $row) {
                            $menu_item_current = MenuItem::findFirst([
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND module_id = $id AND active = 'Y' AND module_name = 'news_menu'",
                                "order" => "id DESC"
                            ]);

                            if (!$menu_item_current) {
                                if (count($row->menu_item) > 0) {
                                    $menu_item_category = MenuItem::findFirst([
                                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu'",
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
                                    'module_name' => 'news_menu',
                                    'name' => $category->name,
                                    'url' => $category->slug,
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
                    $category->menu = 'N';
                    $menu = Menu::find([
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND active = 'Y' AND main = 'Y'"
                    ]);
                    if (count($menu) > 0) {
                        foreach ($menu as $row) {
                            $menuItem = MenuItem::findFirst([
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = " . $row->id . " AND module_name = 'news_menu' AND module_id = $id"
                            ]);
                            if ($menuItem) {
                                $menuItem->delete();
                            }
                        }
                    }
                }

                $category->save();

                if (count($this->_tmpSubdomainLanguages) > 0) {
                    foreach ($this->_tmpSubdomainLanguages as $tmp) {
                        $langId = $tmp->language->id;
                        $langCode = $tmp->language->code;
                        if ($langCode != 'vi') {
                            $categoryLang = NewsMenu::findFirst([
                                'conditions' => 'subdomain_id = '.$this->_get_subdomainID().' AND depend_id = '. $item->id .' AND language_id = '. $langId .''
                            ]);

                            if ($categoryLang) {
                                $categoryLangId = $categoryLang->id;
                                //save active
                                $activeValue = $this->request->getPost('active_' . $categoryLang->depend_id);
                                if (!empty($activeValue)) {
                                    $categoryLang->active = 'Y';
                                } else {
                                    $categoryLang->active = 'N';
                                }

                                //save home
                                $showHomeValue = $this->request->getPost('home_' . $categoryLang->depend_id);
                                if (!empty($showHomeValue)) {
                                    $categoryLang->home = 'Y';
                                } else {
                                    $categoryLang->home = 'N';
                                }

                                //save menu
                                $menuValue = $this->request->getPost('menu_' . $categoryLang->depend_id);
                                if (!empty($menuValue)) {
                                    $categoryLang->menu = 'Y';
                                    $menu = Menu::find([
                                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND active = 'Y' AND main = 'Y'"
                                    ]);
                                    if (count($menu) > 0) {
                                        foreach ($menu as $keyMenu => $row) {
                                            $menu_item_current = MenuItem::findFirst([
                                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND module_id = $categoryLangId AND active = 'Y' AND module_name = 'news_menu'",
                                                "order" => "id DESC"
                                            ]);

                                            if (!$menu_item_current) {
                                                if (count($row->menu_item) > 0) {
                                                    $menu_item_category = MenuItem::findFirst([
                                                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu'",
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
                                                    'module_id' => $categoryLangId,
                                                    'depend_id' => $menuItemIdArr[$keyMenu],
                                                    'level' => 0,
                                                    'module_name' => 'news_menu',
                                                    'name' => $categoryLang->name,
                                                    'url' => $categoryLang->slug,
                                                    'active' => 'Y',
                                                    'sort' => $sort
                                                ]);

                                                $menuItemLang->save();
                                            }
                                        }
                                    }
                                } else {
                                    $categoryLang->menu = 'N';
                                    $menu = Menu::find([
                                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
                                    ]);
                                    if (count($menu) > 0) {
                                        foreach ($menu as $row) {
                                            $menuItem = MenuItem::findFirst([
                                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = " . $row->id . " AND language_id = $langId AND module_name = 'news_menu' AND module_id = $categoryLangId"
                                            ]);
                                            if ($menuItem) {
                                                $menuItem->delete();
                                            }
                                        }
                                    }
                                }

                                //save footer
                                $footerValue = $this->request->getPost('footer_' . $categoryLang->depend_id);
                                if (!empty($footerValue)) {
                                    $categoryLang->footer = 'Y';
                                } else {
                                    $categoryLang->footer = 'N';
                                }

                                //save policy
                                $policyValue = $this->request->getPost('policy_' . $categoryLang->depend_id);
                                if (!empty($policyValue)) {
                                    $categoryLang->policy = 'Y';
                                } else {
                                    $categoryLang->policy = 'N';
                                }

                                //save popup
                                $popupValue = $this->request->getPost('popup_' . $categoryLang->depend_id);
                                if (!empty($popupValue)) {
                                    $categoryLang->popup = 'Y';
                                } else {
                                    $categoryLang->popup = 'N';
                                }

                                //save reg_form
                                $regFormValue = $this->request->getPost('reg_form_' . $categoryLang->depend_id);
                                if (!empty($regFormValue)) {
                                    $categoryLang->reg_form = 'Y';
                                } else {
                                    $categoryLang->reg_form = 'N';
                                }

                                //save sort
                                $sortValue = $this->request->getPost('sort_' . $categoryLang->depend_id);
                                if (!empty($sortValue)) {
                                    //save sort
                                    $categoryLang->sort = $sortValue;
                                } else {
                                    $categoryLang->sort = 1;
                                }

                                $categoryLang->save();
                            }
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["edit"]);
            $url = ACP_NAME . '/' . $this->_getControllerName();
            $this->response->redirect($url);
        }

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->items = $items;
        $this->view->page_current = $page_current;
    }

    /**
     * create new category news
     * 
     * @return View|Phalcon\Http\Response
     */
    public function createAction()
    {
        $random = new Random();
        if ($this->cookies->has('row_id_news_menu_' . $this->_get_subdomainID())) {
            // Get the cookie
            $rowIdCookie = $this->cookies->get('row_id_news_menu_' . $this->_get_subdomainID());

            // Get the cookie's value
            $row_id = $rowIdCookie->getValue();
        } else {
            $row_id = $random->hex(10);
            $this->cookies->set(
                'row_id_news_menu_' . $this->_get_subdomainID(),
                $row_id,
                time() + ROW_ID_COOKIE_TIME
            );
        }

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news_menu/'. $row_id;
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
                    if ($this->cookies->has('row_id_news_menu_' . $langCode . '_' . $this->_get_subdomainID())) {
                        // Get the cookie
                        $rowIdCookie = $this->cookies->get('row_id_news_menu_' . $langCode . '_' . $this->_get_subdomainID());

                        // Get the cookie's value
                        $row_id_cookie = $rowIdCookie->getValue();
                    } else {
                        $row_id_cookie = $random->hex(10);
                        $this->cookies->set(
                            'row_id_news_menu_' . $langCode . '_' . $this->_get_subdomainID(),
                            $row_id_cookie,
                            time() + ROW_ID_COOKIE_TIME
                        );
                    }

                    $row_id_lang[$langCode] = $row_id_cookie;

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news_menu/'. $row_id_lang[$langCode];
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

        $form = new NewsMenuForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new NewsMenu();
            $general = new General();
            $menuActive = ($this->request->getPost('menu') == 'Y') ? 'Y' : 'N';
            $regFrom = ($this->request->getPost('reg_form') == 'Y') ? 'Y' : 'N';
            $messengerForm = ($this->request->getPost('messenger_form') == 'Y') ? 'Y' : 'N';

            $slug = $this->mainGlobal->validateUrlPageCreate($this->request->getPost('slug')) ? $this->request->getPost('slug') : $this->request->getPost('slug') . '-' . mt_rand(100, 999);
            
            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'parent_id' => $this->request->getPost('parent_id'),
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'summary' => $this->request->getPost('summary'),
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'content' => str_replace("public/files/", "files/", $this->request->getPost('content')),
                'sort' => $this->request->getPost('sort'),
                'menu' => ($this->request->getPost('menu') == 'Y') ? 'Y' : 'N',
                'active' => ($this->request->getPost('active') == 'Y') ? 'Y' : 'N',
                'row_id' => $this->request->getPost('row_id'),
                'icon_type' => $this->request->getPost('icon_type'),
                'font_class' => $this->request->getPost('font_class'),
                // 'popup' => $this->request->getPost('popup'),
                'reg_form' => $regFrom,
                'messenger_form' => $messengerForm,
            ];

            if ($data['parent_id'] != 0) {
                $item_parent = NewsMenu::findFirst(
                    [
                        'columns' => 'level',
                        'conditions' => 'id = '. $data['parent_id'] .''
                    ]
                );
                $data['level'] = $item_parent->level + 1;
            }

            $subFolder = $this->_get_subdomainFolder();
            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    $ext = $file->getType();
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
                                $parentLang = NewsMenu::findFirst([
                                    'conditions' => 'depend_id =  '. $this->request->getPost('parent_id') .' AND language_id = '. $langId .' AND subdomain_id = '. $this->_get_subdomainID() .''
                                ]);

                                $parent_id = $parentLang ? $parentLang->id : 0;
                            } else {
                                $parent_id = 0;
                            }
                            
                            $slug = $this->mainGlobal->validateUrlPageCreate($this->request->getPost('slug_' . $langCode), $langId) ? $this->request->getPost('slug_' . $langCode) : $this->request->getPost('slug_' . $langCode) . '-' . mt_rand(100, 999);

                            $itemVi = $item->toArray();
                            $itemVi['language_id'] = $langId;
                            $itemVi['depend_id'] = $id;
                            $itemVi['parent_id'] = $parent_id;
                            $itemVi['name'] = $this->request->getPost('name_' . $langCode);
                            $itemVi['slug'] = $slug;
                            $itemVi['title'] = $this->request->getPost('title_' . $langCode);
                            $itemVi['summary'] = $this->request->getPost('summary_' . $langCode);
                            $itemVi['keywords'] = $this->request->getPost('keywords_' . $langCode);
                            $itemVi['description'] = $this->request->getPost('description_' . $langCode);
                            $itemVi['summary'] = $this->request->getPost('summary_' . $langCode);
                            $itemVi['content'] = str_replace("public/files/", "files/", $this->request->getPost('content_' . $langCode));
                            unset($itemVi['id']);

                            $newsMenuLang = new NewsMenu();
                            $newsMenuLang->assign($itemVi);
                            $newsMenuLang->save();
                        }
                    }
                }

                
                $this->cookies->get('row_id_news_menu_' . $this->_get_subdomainID())->delete();
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langCode = $tmp->language->code;
                    $this->cookies->get('row_id_news_menu_' . $langCode . '_' . $this->_get_subdomainID())->delete();
                }

                $this->flashSession->success($this->_message["add"]);
                if ($item->menu != 'Y') {
                    $this->flashSession->warning("Bạn chưa click chọn menu top!");
                }

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

        $list = $this->recursive(0);

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->list = $list;
        $this->view->row_id = $row_id;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    /**
     * Saves the category news from the 'update' action
     * @param  integer  $id   
     * @param  integer $page 
     * 
     * @return View|Phalcon\Http\Response
     */
    public function updateAction($id, $page = 1)
    {
        $item = NewsMenu::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\NewsMenu.id = $id"
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
                    $itemLang = NewsMenu::findFirst(array(
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
                        if ($this->cookies->has('row_id_news_menu_' . $langCode . '_' . $this->_get_subdomainID())) {
                            // Get the cookie
                            $rowIdCookie = $this->cookies->get('row_id_news_menu_' . $langCode . '_' . $this->_get_subdomainID());

                            // Get the cookie's value
                            $row_id_cookie = $rowIdCookie->getValue();
                        } else {
                            $row_id_cookie = $random->hex(10);
                            $this->cookies->set(
                                'row_id_news_menu_' . $langCode . '_' . $this->_get_subdomainID(),
                                $row_id_cookie,
                                time() + ROW_ID_COOKIE_TIME
                            );
                        }

                        $row_id_lang[$langCode] = $row_id_cookie;
                    }

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news_menu/'. $row_id_lang[$langCode];
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

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news_menu/'. $row_id;
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

        $form = new NewsMenuForm($itemFormData, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $general = new General();
            $slug = $this->mainGlobal->validateUrlPageUpdate($id, $this->request->getPost('slug'), 'news_menu') ? $this->request->getPost('slug') : $this->request->getPost('slug') . '-' . mt_rand(100, 999);

            $data = [
                'parent_id' => $this->request->getPost('parent_id'),
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'summary' => $this->request->getPost('summary'),
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'content' => str_replace("public/files/", "files/", $this->request->getPost('content')),
                'sort' => $this->request->getPost('sort'),
                'active' => ($this->request->getPost('active') == 'Y') ? 'Y' : 'N',
                'row_id' => $this->request->getPost('row_id'),
                'icon_type' => $this->request->getPost('icon_type'),
                'font_class' => $this->request->getPost('font_class'),
                'menu' => ($this->request->getPost('menu') == 'Y') ? 'Y' : 'N',
                'reg_form' => ($this->request->getPost('reg_form') == 'Y') ? 'Y' : 'N',
                'messenger_form' => ($this->request->getPost('messenger_form') == 'Y') ? 'Y' : 'N',
            ];

            if ($data['parent_id'] == 0) {
                $data['level'] = 0;
                if ($item->level != 0) {
                    $itemChilds = NewsMenu::find([
                        'columns' => 'id, level',
                        'conditions' => 'parent_id = '. $item->id .''
                    ]);
                    if (count($itemChilds) > 0) {
                        foreach ($itemChilds as $itemChild) {
                            $level = $itemChild->level - $item->level;
                            $itemChildRow = NewsMenu::findFirstById($itemChild->id);
                            $itemChildRow->assign(['level' => $level]);
                            $itemChildRow->save();
                        }
                    }
                }
            } else {
                $item_parent = NewsMenu::findFirst(
                    [
                        'columns' => 'level',
                        'conditions' => 'id = '. $data['parent_id'] .''
                    ]
                );
                $data['level'] = $item_parent->level + 1;

                if ($item->level == 0) {
                    $itemChilds = NewsMenu::find([
                        'columns' => 'id, level',
                        'conditions' => 'parent_id = '. $item->id .''
                    ]);
                    if (count($itemChilds) > 0) {
                        foreach ($itemChilds as $itemChild) {
                            $level = $itemChild->level + $item_parent->level + 1;
                            $itemChildRow = NewsMenu::findFirstById($itemChild->id);
                            $itemChildRow->assign(['level' => $level]);
                            $itemChildRow->save();
                        }
                    }
                }
            }

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
                            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND module_id = $id AND active = 'Y' AND module_name = 'news_menu'",
                        ]);
                        if ($menuItem) {
                            if ($item->menu == 'Y') {
                                if ($data['parent_id'] == 0) {
                                    $menuItem->assign([
                                        'name' => $this->request->getPost('name'),
                                        'url' => $slug
                                    ]);

                                    $menuItem->save();
                                } else {
                                    $menuItem->delete();
                                }
                            } else {
                                $menuItem->delete();
                            }
                        } else {
                            if ($item->menu == 'Y') {
                                if (count($row->menu_item) > 0) {
                                    $menu_item_category = MenuItem::findFirst([
                                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND language_id = 1 AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu'",
                                        "order" => "id DESC"
                                    ]);
                                    $sort = (!empty($menu_item_category)) ? $menu_item_category->sort + 1 : 3;
                                } else {
                                    $sort = 1;
                                }

                                $menuItem = new MenuItem();
                                $menuItem->assign([
                                    'subdomain_id' => $this->_get_subdomainID(),
                                    'menu_id' => $row->id,
                                    'parent_id' => 0,
                                    'module_id' => $item->id,
                                    'level' => 0,
                                    'module_name' => 'news_menu',
                                    'name' => $item->name,
                                    'url' => $item->slug,
                                    'active' => 'Y',
                                    'sort' => $sort
                                ]);

                                $menuItem->save();
                            }
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
                            if ($this->request->getPost('parent_id') != 0) {
                                $parentLang = NewsMenu::findFirst([
                                    'conditions' => 'depend_id = '. $this->request->getPost('parent_id') .' AND language_id = '. $langId .' AND subdomain_id = '. $this->_get_subdomainID() .''
                                ]);

                                $parent_id = $parentLang ? $parentLang->id : 0;
                            } else {
                                $parent_id = 0;
                            }
                            
                            $slug = $this->mainGlobal->validateUrlPageCreate($this->request->getPost('slug_' . $langCode), $langId) ? $this->request->getPost('slug_' . $langCode) : $this->request->getPost('slug_' . $langCode) . '-' . mt_rand(100, 999);

                            $itemVi = $item->toArray();
                            $itemVi['language_id'] = $langId;
                            $itemVi['depend_id'] = $id;
                            $itemVi['parent_id'] = $parent_id;
                            $itemVi['name'] = $this->request->getPost('name_' . $langCode);
                            $itemVi['slug'] = $slug;
                            $itemVi['title'] = $this->request->getPost('title_' . $langCode);
                            $itemVi['summary'] = $this->request->getPost('summary_' . $langCode);
                            $itemVi['keywords'] = $this->request->getPost('keywords_' . $langCode);
                            $itemVi['description'] = $this->request->getPost('description_' . $langCode);
                            $itemVi['summary'] = $this->request->getPost('summary_' . $langCode);
                            $itemVi['content'] = str_replace("public/files/", "files/", $this->request->getPost('content_' . $langCode));
                            unset($itemVi['id']);

                            // update parent id item child
                            if ($itemVi['parent_id'] == 0) {
                                $itemVi['level'] = 0;
                                if ($item->level != 0) {
                                    $itemChilds = NewsMenu::find([
                                        'columns' => 'id, level',
                                        'conditions' => 'parent_id = '. $item->id .''
                                    ]);
                                    if (count($itemChilds) > 0) {
                                        foreach ($itemChilds as $itemChild) {
                                            $level = $itemChild->level - $item->level;
                                            $itemChildRow = NewsMenu::findFirstById($itemChild->id);
                                            $itemChildRow->assign(['level' => $level]);
                                            $itemChildRow->save();
                                        }
                                    }
                                }
                            } else {
                                $item_parent = NewsMenu::findFirst(
                                    [
                                        'columns' => 'level',
                                        'conditions' => 'id = '. $data['parent_id'] .''
                                    ]
                                );
                                $itemVi['level'] = $item_parent->level + 1;

                                if ($item->level == 0) {
                                    $itemChilds = NewsMenu::find([
                                        'columns' => 'id, level',
                                        'conditions' => 'parent_id = '. $item->id .''
                                    ]);
                                    if (count($itemChilds) > 0) {
                                        foreach ($itemChilds as $itemChild) {
                                            $level = $itemChild->level + $item_parent->level + 1;
                                            $itemChildRow = NewsMenu::findFirstById($itemChild->id);
                                            $itemChildRow->assign(['level' => $level]);
                                            $itemChildRow->save();
                                        }
                                    }
                                }
                            }

                            $newsMenuLang = NewsMenu::findFirst(array(
                                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .' AND depend_id = '. $id .''
                            ));
                            if (!$newsMenuLang) {
                                $newsMenuLang = new NewsMenu();
                            } else {
                                // update depend_id for childs
                                $newsMenuChilds = NewsMenu::findByParentId($id);
                                if (count($newsMenuChilds) > 0) {
                                    foreach ($newsMenuChilds as $newsMenuChild) {
                                        $newsMenuChildLang = NewsMenu::findFirst([
                                            'conditions' => 'subdomain_id = '.$this->_get_subdomainID().' AND depend_id =  '. $newsMenuChild->id .' AND language_id = '. $langId .''
                                        ]);

                                        if ($newsMenuChildLang) {
                                            $newsMenuChildLang->parent_id = $newsMenuLang->id;
                                            $newsMenuChildLang->save();
                                        }
                                    }
                                }
                            }

                            $newsMenuLang->assign($itemVi);
                            if ($newsMenuLang->save()) {
                                $menu = Menu::find([
                                    "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND active = 'Y' AND main = 'Y'"
                                ]);

                                if (count($menu) > 0) {
                                    foreach ($menu as $row) {
                                        $menuItem = MenuItem::findFirst([
                                            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND language_id = $langId AND menu_id = ". $row->id ." AND module_id = $id AND active = 'Y' AND module_name = 'news_menu'",
                                        ]);
                                        if ($menuItem) {
                                            if ($newsMenuLang->menu == 'Y') {
                                                if ($itemVi['parent_id'] == 0) {
                                                    $menuItem->assign([
                                                        'name' => $this->request->getPost('name_' . $langCode),
                                                        'url' => $slug
                                                    ]);

                                                    $menuItem->save();
                                                } else {
                                                    $menuItem->delete();
                                                }
                                            } else {
                                                $menuItem->delete();
                                            }
                                        } else {
                                            if ($newsMenuLang->menu == 'Y') {
                                                if (count($row->menu_item) > 0) {
                                                    $menuItemNewsMenu = MenuItem::findFirst([
                                                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND language_id = $langId AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu'",
                                                        "order" => "id DESC"
                                                    ]);
                                                    $sort = (!empty($menuItemNewsMenu)) ? $menuItemNewsMenu->sort + 1 : 3;
                                                } else {
                                                    $sort = 1;
                                                }

                                                $menuItem = new MenuItem();
                                                $menuItem->assign([
                                                    'subdomain_id' => $this->_get_subdomainID(),
                                                    'menu_id' => $row->id,
                                                    'parent_id' => 0,
                                                    'language_id' => $langId,
                                                    'module_id' => $newsMenuLang->id,
                                                    'level' => 0,
                                                    'module_name' => 'news_menu',
                                                    'name' => $newsMenuLang->name,
                                                    'url' => $newsMenuLang->slug,
                                                    'active' => 'Y',
                                                    'sort' => $sort
                                                ]);

                                                $menuItem->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langCode = $tmp->language->code;
                    $this->cookies->get('row_id_news_menu_' . $langCode . '_' . $this->_get_subdomainID())->delete();
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

        $list = $this->recursive(0);

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->list = $list;
        $this->view->item = $item;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->row_id = $row_id;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function showAction($id, $page = 1)
    {
        $item = NewsMenu::findFirstById($id);
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
                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu'",
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
        $item = NewsMenu::findFirstById($id);
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
                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'N' AND module_name = 'news_menu'",
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
            $item = NewsMenu::findFirstById($id);
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
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu'",
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
            $item = NewsMenu::findFirstById($id);
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
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu'",
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
            $this->flash->error("NewsMenu was not found");
        }
        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;
        $this->response->redirect($url);
    }

    public function deleteAction($id, $page = 1)
    {
        $item = NewsMenu::findFirst([
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
                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu'",
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
            $item = NewsMenu::findFirst([
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
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu'",
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
        $item = NewsMenu::findFirst([
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
        $newsMenuChild = NewsMenu::find(['conditions' => 'Modules\Models\NewsMenu.parent_id = '. $id .' AND subdomain_id = '. $this->_get_subdomainID() .'']);
        if (count($newsMenuChild) > 0) {
            $this->flashSession->error('Bạn phải xóa danh mục con trước khi xóa danh mục cha');
            return $this->response->redirect($url);
        }

        if (!$item->delete()) {
            foreach ($item->getMessages() as $message) {
                $this->flashSession->error($message);
            }
        } else {
            //delete other lang item
            $dependNewsMenus = NewsMenu::findByDependId($id);

            // delete tmp news menu id elastic
            $this->elastic_service->deleteTmpNewsMenu($id);
            if (count($dependNewsMenus) > 0) {
                foreach ($dependNewsMenus as $dependNewsMenu) {
                    if ($dependNewsMenu->delete()) {
                        if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news_menu/" . $dependNewsMenu->row_id)) {
                            $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news_menu/" . $dependNewsMenu->row_id);
                        }

                        // delete tmp news menu id elastic
                        $this->elastic_service->deleteTmpNewsMenu($dependNewsMenu->id);
                    }
                }
            }
            
            @unlink("files/icon/" . $sub_folder . "/" . $icon);
            TmpNewsNewsMenu::deleteByRawSql('news_menu_id ='. $id .'');
            $menu = Menu::find([
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
            ]);

            if (count($menu) > 0) {
                foreach ($menu as $row) {
                    $menuItems = MenuItem::find([
                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu' AND module_id = $id",
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
                                $newsMenuLang = NewsMenu::findFirst([
                                    'conditions' => 'language_id = '. $langId .' AND subdomain_id = '.$this->_get_subdomainID().' AND depend_id =  '. $id .''
                                ]);
                                if ($newsMenuLang) {
                                    $menuItems = MenuItem::find([
                                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu' AND module_id = $newsMenuLang->id",
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

            if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news_menu/" . $item->row_id)) {
                $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news_menu/" . $item->row_id);
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
            $item = NewsMenu::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $icon = $item->icon;
                $sub_folder = $this->_get_subdomainFolder();
                $newsMenuChild = NewsMenu::find(['conditions' => 'parent_id = '. $id .' AND Modules\Models\NewsMenu.subdomain_id = '. $this->_get_subdomainID() .'']);
                if (count($newsMenuChild) > 0) {
                    $this->flashSession->error('Bạn phải xóa danh mục con trước khi xóa danh mục cha');
                    return $this->response->redirect($url);
                }
                if (!$item->delete()) {
                    foreach ($item->getMessages() as $message) {
                        $this->flashSession->error($message);
                    }
                } else {
                    // delete tmp news menu id elastic
                    $this->elastic_service->deleteTmpNewsMenu($id);
                    //delete other lang item
                    $dependNewsMenus = NewsMenu::findByDependId($id);
                    if (count($dependNewsMenus) > 0) {
                        foreach ($dependNewsMenus as $dependNewsMenu) {
                            if ($dependNewsMenu->delete()) {
                                if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news_menu/" . $dependNewsMenu->row_id)) {
                                    $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news_menu/" . $dependNewsMenu->row_id);
                                }

                                // delete tmp news menu id elastic
                                $this->elastic_service->deleteTmpNewsMenu($dependNewsMenu->id);
                            }
                        }
                    }

                    @unlink("files/icon/" . $sub_folder . "/" . $icon);
                    TmpNewsNewsMenu::deleteByRawSql('news_menu_id ='. $id .'');
                    $menu = Menu::find([
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
                    ]);

                    if (count($menu) > 0) {
                        foreach ($menu as $row) {
                            $menuItems = MenuItem::find([
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu' AND module_id = $id",
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
                                        $newsMenuLang = NewsMenu::findFirst([
                                            'conditions' => 'language_id = '. $langId .' AND subdomain_id = '.$this->_get_subdomainID().' AND depend_id =  '. $id .''
                                        ]);
                                        if ($newsMenuLang) {
                                            $menuItems = MenuItem::find([
                                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu' AND module_id = $newsMenuLang->id",
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
                    
                    if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news_menu/" . $item->row_id)) {
                        $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news_menu/" . $item->row_id);
                    }
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

    public function updateSubdomainIdAction()
    {
        $tmpNewsNewsMenus = TmpNewsNewsMenu::findBySubdomainId(0);
        foreach ($tmpNewsNewsMenus as $tmpNewsNewsMenu) {
            if ($tmpNewsNewsMenu->news) {
                $tmpNewsNewsMenu->subdomain_id = $tmpNewsNewsMenu->news->subdomain_id;
                $tmpNewsNewsMenu->save();
            }
        }
    }

    public function recursive($parent_id = 0, $langId = 1, $space = "", $trees = array())
    {
        if (!$trees) {
            $trees = [];
        }
        $result = NewsMenu::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "parent_id = ". $parent_id ." AND subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND deleted = 'N'"
            ]
        );

        $trees_obj = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $row->name = $space . $row->name;
                $trees[] = $row;

                $trees   = $this->recursive($row->id, $langId, $space . '|---', $trees);
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

    public function count_child($id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['n' => 'Modules\Models\NewsMenu'])
            ->where('n.parent_id = '. $id .' AND n.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }

    public function count_news($id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['n' => 'Modules\Models\News'])
            ->join('Modules\Models\TmpNewsNewsMenu', 'tmp.news_id = n.id', 'tmp')
            ->where('tmp.news_subdomain_id = ".$this->_get_subdomainID()." AND menu_id = '. $id .' AND n.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }

    private function deleteCache()
    {
        
        $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'news_menu']);
    }
}
