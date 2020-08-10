<?php namespace Modules\Backend\Controllers;

use Modules\Models\Category;
use Modules\Models\Menu;
use Modules\Models\MenuItem;
use Modules\Models\TmpProductCategory;
use Modules\Forms\CategoryForm;
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
use Phalcon\Image\Adapter\GD;
use Phalcon\Paginator\Adapter\NativeArray as PaginatorArray;

class CategoryController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Danh mục sản phẩm';
        $this->_message = $this->getMessage();
    }

    /**
     * display list category
     * 
     * @return View|Phalcon\Http\Response
     */
    public function indexAction()
    {
        $items = $this->recursive(0);
        $page_current = 1;

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new PaginatorArray(
            array(
                "data" => $items,
                "limit" => 20,
                "page" => $numberPage
            )

        );

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        if ($this->request->isPost()) {
            foreach ($paginator->getPaginate()->items as $item) {
                $id = $item->id;
                $category = Category::findFirstById($item->id);

                //save active
                $activeValue = $this->request->getPost('active_' . $category->id);
                if (!empty($activeValue)) {
                    $category->active = 'Y';
                } else {
                    $category->active = 'N';
                }

                //save hot
                $hotValue = $this->request->getPost('hot_' . $category->id);
                if (!empty($hotValue)) {
                    $category->hot = 'Y';
                } else {
                    $category->hot = 'N';
                }

                //save home
                $showHomeValue = $this->request->getPost('show_home_' . $category->id);
                if (!empty($showHomeValue)) {
                    $category->show_home = 'Y';
                } else {
                    $category->show_home = 'N';
                }

                //save picture
                $pictureValue = $this->request->getPost('picture_' . $category->id);
                if (!empty($pictureValue)) {
                    $category->picture = 'Y';
                } else {
                    $category->picture = 'N';
                }

                //save sort
                $sortValue = $this->request->getPost('sort_' . $item->id);
                if (!empty($sortValue)) {
                    //save sort
                    $category->sort = $sortValue;
                } else {
                    $category->sort = 1;
                }

                //save sort
                $sortHomeValue = $this->request->getPost('sort_home_' . $item->id);
                if (!empty($sortHomeValue)) {
                    //save sort
                    $category->sort_home = $sortHomeValue;
                } else {
                    $category->sort_home = 1;
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
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND module_id = $id AND active = 'Y' AND module_name = 'category'",
                                "order" => "id DESC"
                            ]);

                            if (!$menu_item_current) {
                                if (count($row->menu_item) > 0) {
                                    $menuItemCategory = MenuItem::findFirst([
                                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'category'",
                                        "order" => "sort DESC"
                                    ]);
                                    
                                    $sort = ($menuItemCategory) ? $menuItemCategory->sort + 1 : 3;
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
                                    'module_name' => 'category',
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
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = " . $row->id . " AND module_name = 'category' AND module_id = $id"
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
                            $categoryLang = Category::findFirst([
                                'conditions' => 'depend_id = '. $item->id .' AND language_id = '. $langId .''
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

                                $hotValue = $this->request->getPost('hot_' . $categoryLang->depend_id);
                                if (!empty($hotValue)) {
                                    $categoryLang->hot = 'Y';
                                } else {
                                    $categoryLang->hot = 'N';
                                }

                                //save home
                                $showHomeValue = $this->request->getPost('show_home_' . $categoryLang->depend_id);
                                if (!empty($showHomeValue)) {
                                    $categoryLang->show_home = 'Y';
                                } else {
                                    $categoryLang->show_home = 'N';
                                }

                                //save picture
                                $pictureValue = $this->request->getPost('picture_' . $categoryLang->depend_id);
                                if (!empty($pictureValue)) {
                                    $categoryLang->picture = 'Y';
                                } else {
                                    $categoryLang->picture = 'N';
                                }

                                //save sort
                                $sortValue = $this->request->getPost('sort_' . $categoryLang->depend_id);
                                if (!empty($sortValue)) {
                                    //save sort
                                    $categoryLang->sort = $sortValue;
                                } else {
                                    $categoryLang->sort = 1;
                                }

                                //save sort
                                $sortHomeValue = $this->request->getPost('sort_' . $categoryLang->depend_id);
                                if (!empty($sortHomeValue)) {
                                    //save sort
                                    $categoryLang->sort_home = $sortHomeValue;
                                } else {
                                    $categoryLang->sort_home = 1;
                                }

                                //save menu
                                $menuValue = $this->request->getPost('menu_' . $item->id);
                                if (!empty($menuValue)) {
                                    $categoryLang->menu = 'Y';
                                    $menu = Menu::find([
                                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND active = 'Y' AND main = 'Y'"
                                    ]);

                                    if (count($menu) > 0) {
                                        foreach ($menu as $keyMenu => $row) {
                                            $menu_item_current = MenuItem::findFirst([
                                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND module_id = $categoryLangId AND active = 'Y' AND module_name = 'category'",
                                                "order" => "id DESC"
                                            ]);

                                            if (!$menu_item_current) {
                                                if (count($row->menu_item) > 0) {
                                                    $menuItemCategory = MenuItem::findFirst([
                                                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'category'",
                                                        "order" => "sort DESC"
                                                    ]);
                                                    
                                                    $sort = ($menuItemCategory) ? $menuItemCategory->sort + 1 : 3;
                                                } else {
                                                    $sort = 1;
                                                }
                                                
                                                
                                                $menuItemLang = new MenuItem();
                                                $menuItemLang->assign([
                                                    'subdomain_id' => $this->_get_subdomainID(),
                                                    'menu_id' => $row->id,
                                                    'parent_id' => 0,
                                                    'module_id' => $categoryLangId,
                                                    'depend_id' => $menuItemIdArr[$keyMenu],
                                                    'language_id' => $langId,
                                                    'level' => 0,
                                                    'module_name' => 'category',
                                                    'name' => $categoryLang->name,
                                                    'url' => $categoryLang->slug,
                                                    'active' => 'Y',
                                                    'sort' => $sort
                                                ]);

                                                if (!$menuItemLang->save()) {
                                                    foreach ($menuItemLang->getMessages() as $message) {
                                                        $this->flashSession->error($message);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $categoryLang->menu = 'N';
                                    $menu = Menu::find([
                                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND active = 'Y' AND main = 'Y'"
                                    ]);
                                    if (count($menu) > 0) {
                                        foreach ($menu as $row) {
                                            $menuItem = MenuItem::findFirst([
                                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = " . $row->id . " AND module_name = 'category' AND module_id = $categoryLangId"
                                            ]);
                                            if ($menuItem) {
                                                $menuItem->delete();
                                            }
                                        }
                                    }
                                }

                                $categoryLang->save();
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
        $this->view->items = $items;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
    }

    /**
     * create new category
     * 
     * @return View|Phalcon\Http\Response
     */
    public function createAction()
    {
        $form = new CategoryForm();

        $random = new Random();
        if ($this->cookies->has('row_id_category_' . $this->_get_subdomainID())) {
            // Get the cookie
            $rowIdCookie = $this->cookies->get('row_id_category_' . $this->_get_subdomainID());

            // Get the cookie's value
            $row_id = $rowIdCookie->getValue();
        } else {
            $row_id = $random->hex(10);
            $this->cookies->set(
                'row_id_category_' . $this->_get_subdomainID(),
                $row_id,
                time() + ROW_ID_COOKIE_TIME
            );
        }

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/category/'. $row_id;
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
                    if ($this->cookies->has('row_id_category_' . $langCode . '_' . $this->_get_subdomainID())) {
                        // Get the cookie
                        $rowIdCookie = $this->cookies->get('row_id_category_' . $langCode . '_' . $this->_get_subdomainID());

                        // Get the cookie's value
                        $row_id_cookie = $rowIdCookie->getValue();
                    } else {
                        $row_id_cookie = $random->hex(10);
                        $this->cookies->set(
                            'row_id_category_' . $langCode . '_' . $this->_get_subdomainID(),
                            $row_id_cookie,
                            time() + ROW_ID_COOKIE_TIME
                        );
                    }

                    $row_id_lang[$langCode] = $row_id_cookie;

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/category/'. $row_id_lang[$langCode];
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

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new Category();
            $general = new General();

            $slug = $this->mainGlobal->validateUrlPageCreate($this->request->getPost('slug')) ? $this->request->getPost('slug') : $this->request->getPost('slug') . '-' . mt_rand(100, 999);

            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'parent_id' => $this->request->getPost('parent_id'),
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'content' => str_replace("public/files/", "files/", $this->request->getPost('content')),
                'sort' => $this->request->getPost('sort'),
                'menu' => $this->request->getPost('menu'),
                'active' => $this->request->getPost('active'),
                'row_id' => $this->request->getPost('row_id'),
                'icon_type' => $this->request->getPost('icon_type'),
                'font_class' => $this->request->getPost('font_class'),
                'picture' => ($this->request->getPost('picture') == 'Y') ? 'Y' : 'N'
            ];

            if ($data['parent_id'] != 0) {
                $item_parent = Category::findFirst(
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

                        // upload for banner
                        if ($file->getKey() == 'banner') {
                            $subfolderUrl = 'files/category/' . $subFolder;
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl, null, 'category');
                            if (!empty($dataUpload['file_name'])) {
                                $data['banner'] = $dataUpload['file_name'];
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }

                        // upload for banner
                        if ($file->getKey() == 'banner_md_sole') {
                            $subfolderUrl = 'files/category/' . $subFolder;
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl, null, 'category');
                            if (!empty($dataUpload['file_name'])) {
                                $data['banner_md_sole'] = $dataUpload['file_name'];
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
                                $parentLang = Category::findFirst([
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
                            $itemVi['keywords'] = $this->request->getPost('keywords_' . $langCode);
                            $itemVi['description'] = $this->request->getPost('description_' . $langCode);
                            $itemVi['summary'] = $this->request->getPost('summary_' . $langCode);
                            $itemVi['content'] = str_replace("public/files/", "files/", $this->request->getPost('content_' . $langCode));
                            unset($itemVi['id']);

                            $categoryLang = new Category();
                            $categoryLang->assign($itemVi);
                            $categoryLang->save();
                        }
                    }
                }

                
                $this->cookies->get('row_id_category_' . $this->_get_subdomainID())->delete();
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $langCode = $tmp->language->code;
                    $this->cookies->get('row_id_category_' . $langCode . '_' . $this->_get_subdomainID())->delete();
                }
                
                $this->flashSession->success($this->_message["add"]);

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/index';
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
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
     * Saves the category from the 'update' action
     * @param  integer  $id   
     * @param  integer $page 
     * 
     * @return View|Phalcon\Http\Response
     */
    public function updateAction($id, $page = 1)
    {
        $item = Category::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND Modules\Models\Category.id = $id"
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
                    $itemLang = Category::findFirst(array(
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
                        if ($this->cookies->has('row_id_category_' . $langCode . '_' . $this->_get_subdomainID())) {
                            // Get the cookie
                            $rowIdCookie = $this->cookies->get('row_id_category_' . $langCode . '_' . $this->_get_subdomainID());

                            // Get the cookie's value
                            $row_id_cookie = $rowIdCookie->getValue();
                        } else {
                            $row_id_cookie = $random->hex(10);
                            $this->cookies->set(
                                'row_id_category_' . $langCode . '_' . $this->_get_subdomainID(),
                                $row_id_cookie,
                                time() + ROW_ID_COOKIE_TIME
                            );
                        }

                        $row_id_lang[$langCode] = $row_id_cookie;
                    }

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/category/'. $row_id_lang[$langCode];
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

        $banner = $item->banner;
        $bannerMdSole = $item->banner_md_sole;
        $icon = $item->icon;
        $row_id = ($item->row_id != 0) ? $item->row_id : $item->id;

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/category/'. $row_id;
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

        $form = new CategoryForm($itemFormData, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $general = new General();

            $slug = $this->mainGlobal->validateUrlPageUpdate($id, $this->request->getPost('slug'), 'category') ? $this->request->getPost('slug') : $this->request->getPost('slug') . '-' . mt_rand(100, 999);

            $data = [
                'parent_id' => $this->request->getPost('parent_id'),
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'content' => str_replace("public/files/", "files/", $this->request->getPost('content')),
                'sort' => $this->request->getPost('sort'),
                'menu' => $this->request->getPost('menu'),
                'active' => $this->request->getPost('active'),
                'row_id' => $this->request->getPost('row_id'),
                'icon_type' => $this->request->getPost('icon_type'),
                'font_class' => $this->request->getPost('font_class'),
                'picture' => ($this->request->getPost('picture') == 'Y') ? 'Y' : 'N'
            ];

            if ($data['parent_id'] == 0) {
                $data['level'] = 0;
                if ($item->level != 0) {
                    $itemChilds = Category::find([
                        'columns' => 'id, level',
                        'conditions' => 'parent_id = '. $item->id .''
                    ]);
                    if (count($itemChilds) > 0) {
                        foreach ($itemChilds as $itemChild) {
                            $level = $itemChild->level - $item->level;
                            $itemChildRow = Category::findFirstById($itemChild->id);
                            $itemChildRow->assign(['level' => $level]);
                            $itemChildRow->save();
                        }
                    }
                }
            } else {
                $item_parent = Category::findFirst(
                    [
                        'columns' => 'level',
                        'conditions' => 'id = '. $data['parent_id'] .''
                    ]
                );
                $data['level'] = $item_parent->level + 1;

                if ($item->level == 0) {
                    $itemChilds = Category::find([
                        'columns' => 'id, level',
                        'conditions' => 'parent_id = '. $item->id .''
                    ]);
                    if (count($itemChilds) > 0) {
                        foreach ($itemChilds as $itemChild) {
                            $level = $itemChild->level + $item_parent->level + 1;
                            $itemChildRow = Category::findFirstById($itemChild->id);
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
                    $ext = $file->getType();
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

                        // upload for banner
                        if ($file->getKey() == 'banner') {
                            $subfolderUrl = 'files/category/' . $subFolder;
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl, null, 'category');
                            if (!empty($dataUpload['file_name'])) {
                                $data['banner'] = $dataUpload['file_name'];
                                @unlink($subfolderUrl . '/' . $banner);
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }

                        // upload for banner
                        if ($file->getKey() == 'banner_md_sole') {
                            $subfolderUrl = 'files/category/' . $subFolder;
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl, null, 'category');
                            if (!empty($dataUpload['file_name'])) {
                                $data['banner_md_sole'] = $dataUpload['file_name'];
                                @unlink($subfolderUrl . '/' . $bannerMdSole);
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
                            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND module_id = $id AND active = 'Y' AND module_name = 'category'",
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
                                    $menuItemCategory = MenuItem::findFirst([
                                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND language_id = 1 AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'category'",
                                        "order" => "id DESC"
                                    ]);
                                    $sort = (!empty($menuItemCategory)) ? $menuItemCategory->sort + 1 : 3;
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
                                    'module_name' => 'category',
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
                                $parentLang = Category::findFirst([
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
                            $itemVi['keywords'] = $this->request->getPost('keywords_' . $langCode);
                            $itemVi['description'] = $this->request->getPost('description_' . $langCode);
                            $itemVi['summary'] = $this->request->getPost('summary_' . $langCode);
                            $itemVi['content'] = str_replace("public/files/", "files/", $this->request->getPost('content_' . $langCode));
                            unset($itemVi['id']);

                            // update parent id item child
                            if ($itemVi['parent_id'] == 0) {
                                $itemVi['level'] = 0;
                                if ($item->level != 0) {
                                    $itemChilds = Category::find([
                                        'columns' => 'id, level',
                                        'conditions' => 'parent_id = '. $item->id .''
                                    ]);
                                    if (count($itemChilds) > 0) {
                                        foreach ($itemChilds as $itemChild) {
                                            $level = $itemChild->level - $item->level;
                                            $itemChildRow = Category::findFirstById($itemChild->id);
                                            $itemChildRow->assign(['level' => $level]);
                                            $itemChildRow->save();
                                        }
                                    }
                                }
                            } else {
                                $item_parent = Category::findFirst(
                                    [
                                        'columns' => 'level',
                                        'conditions' => 'id = '. $itemVi['parent_id'] .''
                                    ]
                                );
                                $itemVi['level'] = $item_parent->level + 1;

                                if ($item->level == 0) {
                                    $itemChilds = Category::find([
                                        'columns' => 'id, level',
                                        'conditions' => 'parent_id = '. $item->id .''
                                    ]);
                                    if (count($itemChilds) > 0) {
                                        foreach ($itemChilds as $itemChild) {
                                            $level = $itemChild->level + $item_parent->level + 1;
                                            $itemChildRow = Category::findFirstById($itemChild->id);
                                            $itemChildRow->assign(['level' => $level]);
                                            $itemChildRow->save();
                                        }
                                    }
                                }
                            }

                            $categoryLang = Category::findFirst(array(
                                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .' AND depend_id = '. $id .''
                            ));
                            if (!$categoryLang) {
                                $categoryLang = new Category();
                            } else {
                                // update depend_id for childs
                                $categoryChilds = Category::findByParentId($id);
                                if (count($categoryChilds) > 0) {
                                    foreach ($categoryChilds as $categoryChild) {
                                        $categoryChildLang = Category::findFirst([
                                            'conditions' => 'depend_id = '. $categoryChild->id .' AND language_id = '. $langId .''
                                        ]);

                                        if ($categoryChildLang) {
                                            $categoryChildLang->parent_id = $categoryLang->id;
                                            $categoryChildLang->save();
                                        }
                                    }
                                }
                            }

                            $categoryLang->assign($itemVi);
                            if ($categoryLang->save()) {
                                $menu = Menu::find([
                                    "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $langId AND active = 'Y' AND main = 'Y'"
                                ]);

                                if (count($menu) > 0) {
                                    foreach ($menu as $row) {
                                        $menuItem = MenuItem::findFirst([
                                            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND module_id = $id AND active = 'Y' AND module_name = 'news_menu'",
                                        ]);
                                        if ($menuItem) {
                                            if ($categoryLang->menu == 'Y') {
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
                                            if ($categoryLang->menu == 'Y') {
                                                if (count($row->menu_item) > 0) {
                                                    $menuItemCategory = MenuItem::findFirst([
                                                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND language_id = $langId AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'category'",
                                                        "order" => "id DESC"
                                                    ]);
                                                    $sort = (!empty($menuItemCategory)) ? $menuItemCategory->sort + 1 : 3;
                                                } else {
                                                    $sort = 1;
                                                }

                                                $menuItem = new MenuItem();
                                                $menuItem->assign([
                                                    'subdomain_id' => $this->_get_subdomainID(),
                                                    'menu_id' => $row->id,
                                                    'parent_id' => 0,
                                                    'language_id' => $langId,
                                                    'module_id' => $categoryLang->id,
                                                    'level' => 0,
                                                    'module_name' => 'category',
                                                    'name' => $categoryLang->name,
                                                    'url' => $categoryLang->slug,
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
                    $this->cookies->get('row_id_category_' . $langCode . '_' . $this->_get_subdomainID())->delete();
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

    public function deleteAction($id, $page = 1)
    {
        $item = Category::findFirst([
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
            if (count($menu) > 0) {
                foreach ($menu as $row) {
                    $menuItem = MenuItem::findFirst([
                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'category'",
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
            $item = Category::findFirst([
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
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'category'",
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
        $item = Category::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        $banner = $item->banner;
        $bannerMdSole = $item->banner_md_sole;
        $icon = $item->icon;
        $sub_folder = $this->_get_subdomainFolder();
        $categoryChild = Category::find(['conditions' => 'Modules\Models\Category.parent_id = '. $id .' AND subdomain_id = '. $this->_get_subdomainID() .'']);
        $general = new General();
        if (count($categoryChild) > 0) {
            $this->flashSession->error('Bạn phải xóa danh mục con trước khi xóa danh mục cha');
            return $this->response->redirect($url);
        }
        if (!$item->delete()) {
            foreach ($item->getMessages() as $message) {
                $this->flashSession->error($message);
            }
        } else {
            TmpProductCategory::deleteByRawSql('category_id ='. $id .'');
            // delete tmp product id elastic
            $this->elastic_service->deleteTmpCategory($id);
            @unlink("files/category/" . $sub_folder . "/" . $banner);
            @unlink("files/category/" . $sub_folder . "/" . $bannerMdSole);
            @unlink("files/icon/" . $sub_folder . "/" . $icon);
            $menu = Menu::find([
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
            ]);

            if (count($menu) > 0) {
                foreach ($menu as $row) {
                    $menuItems = MenuItem::find([
                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'category' AND module_id = $id",
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
                                $categoryLang = Category::findFirst([
                                    'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                ]);
                                if ($categoryLang) {
                                    $menuItems = MenuItem::find([
                                        "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu' AND module_id = $categoryLang->id",
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
            if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/category/" . $item->row_id)) {
                $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/category/" . $item->row_id);
            }

            //delete other lang item
            $dependCategorys = Category::findByDependId($id);
            if (count($dependCategorys) > 0) {
                foreach ($dependCategorys as $dependCategory) {
                    if ($dependCategory->delete()) {
                        if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/category/" . $dependCategory->row_id)) {
                            $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/category/" . $dependCategory->row_id);
                        }

                        // delete tmp product id elastic
                        $this->elastic_service->deleteTmpCategory($dependCategory->id);
                    }
                }
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
            $item = Category::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $banner = $item->banner;
                $bannerMdSole = $item->banner_md_sole;
                $icon = $item->icon;
                $sub_folder = $this->_get_subdomainFolder();
                $categoryChild = Category::find(['conditions' => 'Modules\Models\Category.parent_id = '. $id .' AND subdomain_id = '. $this->_get_subdomainID() .'']);
                if (count($categoryChild) > 0) {
                    $this->flashSession->error('Bạn phải xóa danh mục con trước khi xóa danh mục cha');
                    return $this->response->redirect($url);
                }
                if (!$item->delete()) {
                    foreach ($item->getMessages() as $message) {
                        $this->flashSession->error($message);
                    }
                } else {
                    TmpProductCategory::deleteByRawSql('category_id ='. $id .'');

                    // delete tmp product id elastic
                    $this->elastic_service->deleteTmpCategory($id);

                    @unlink("files/category/" . $sub_folder . "/" . $banner);
                    @unlink("files/icon/" . $sub_folder . "/" . $icon);
                    $menu = Menu::find([
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND active = 'Y' AND main = 'Y'"
                    ]);

                    if (count($menu) > 0) {
                        foreach ($menu as $row) {
                            $menuItems = MenuItem::find([
                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'category' AND module_id = $id",
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
                                        $categoryLang = Category::findFirst([
                                            'conditions' => 'language_id = '. $langId .' AND depend_id = '. $id .''
                                        ]);
                                        if ($categoryLang) {
                                            $menuItems = MenuItem::find([
                                                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND menu_id = ". $row->id ." AND active = 'Y' AND module_name = 'news_menu' AND module_id = $categoryLang->id",
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
                    if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/category/" . $item->row_id)) {
                        $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/category/" . $item->row_id);
                    }

                    //delete other lang item
                    $dependCategorys = Category::findByDependId($id);
                    if (count($dependCategorys) > 0) {
                        foreach ($dependCategorys as $dependCategory) {
                            if ($dependCategory->delete()) {
                                if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/category/" . $dependCategory->row_id)) {
                                    $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/category/" . $dependCategory->row_id);
                                }

                                // delete tmp product id elastic
                                $this->elastic_service->deleteTmpCategory($dependCategory->id);
                            }
                        }
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
        $tmpProductCategories = TmpProductCategory::findBySubdomainId(0);
        foreach ($tmpProductCategories as $tmpProductCategory) {
            if ($tmpProductCategory->product) {
                $tmpProductCategory->subdomain_id = $tmpProductCategory->product->subdomain_id;
                $tmpProductCategory->save();
            }
        }
    }

    public function recursive($parent_id = 0, $langId = 1, $space = "", $trees = array())
    {
        if (!$trees) {
            $trees = [];
        }
        $result = Category::find(
            [
                "columns" => "id, parent_id, level, name, slug, hits, sort, sort_home, active, show_home, menu, hot, picture",
                "order" => "sort ASC, id DESC",
                "conditions" => "parent_id = ". $parent_id ." AND subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1 AND deleted = 'N'"
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

    public function count_child(int $id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['n' => 'Modules\Models\Category'])
            ->where('n.parent_id = '. $id .' AND n.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }

    public function count_product(int $id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['p' => 'Modules\Models\Product'])
            ->join('Modules\Models\TmpProductCategory', 'tmp.product_id = p.id', 'tmp')
            ->where('tmp.category_id = '. $id .' AND p.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }

    private function deleteCache()
    {
        
        $this->elastic_service->updateSubdomain($this->_get_subdomainID(), ['type' => 'category']);
    }
}
