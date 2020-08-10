<?php namespace Modules\Backend\Controllers;

use Modules\Models\Menu;
use Modules\Models\MenuItem;
use Modules\Models\NewsType;
use Modules\Forms\NewsTypeForm;
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
use Phalcon\Security\Random;

/**
 * Modules\Controllers\NewsTypeController
 *
 * CRUD to manage users
 */
class NewsTypeController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Chuyên mục';
    }
    public function indexAction()
    {
        $list = NewsType::find(["order" => "sort ASC, id DESC", "conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND static = 'N' AND deleted = 'N'"]);
        $list_j_category = $this->modelsManager->createBuilder()->columns(['nt.id'])->from(['nt' => 'Modules\Models\NewsType'])->join('Modules\Models\NewsCategory', 'nc.type_id = nt.id', 'nc')->where('nt.deleted = "N" AND nc.deleted = "N"')->groupBy('nt.id')->getQuery()->execute();
        $list_j_news = $this->modelsManager->createBuilder()->columns(['nt.id'])->from(['nt' => 'Modules\Models\NewsType'])->join('Modules\Models\News', 'n.type_id = nt.id', 'n')->where('nt.deleted = "N" AND n.deleted = "N"')->groupBy('nt.id')->getQuery()->execute();
        $arr_list_j_category = array();
        if (!empty($list_j_category)) {
            foreach ($list_j_category as $row) {
                $arr_list_j_category[] = $row->id;
            }
        }
        $arr_list_j_news = array();
        if (!empty($list_j_news)) {
            foreach ($list_j_news as $row) {
                $arr_list_j_news[] = $row->id;
            }
        }
        $numberPage = $this->request->getQuery("page", "int");
        $paginator = new Paginator(["data" => $list, "limit" => 50, "page" => $numberPage]);
        $page_current = ($numberPage > 1) ? $numberPage : 1;
        $breadcrumb = '<li class="active">' . $this->view->module_name . '</li>';
        $this->view->arr_list_j_category = $arr_list_j_category;
        $this->view->arr_list_j_news = $arr_list_j_news;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
    }
    public function staticAction()
    {
        $list = NewsType::find(["order" => "sort ASC, id DESC", "conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND static = 'Y' AND deleted = 'N'"]);
        $list_j_category = $this->modelsManager->createBuilder()->columns(['nt.id'])->from(['nt' => 'Modules\Models\NewsType'])->join('Modules\Models\NewsCategory', 'nc.type_id = nt.id', 'nc')->where('nt.deleted = "N" AND nc.deleted = "N"')->groupBy('nt.id')->getQuery()->execute();
        $list_j_news = $this->modelsManager->createBuilder()->columns(['nt.id'])->from(['nt' => 'Modules\Models\NewsType'])->join('Modules\Models\News', 'n.type_id = nt.id', 'n')->where('nt.deleted = "N" AND n.deleted = "N"')->groupBy('nt.id')->getQuery()->execute();
        $arr_list_j_category = array();
        if (!empty($list_j_category)) {
            foreach ($list_j_category as $row) {
                $arr_list_j_category[] = $row->id;
            }
        }
        $arr_list_j_news = array();
        if (!empty($list_j_news)) {
            foreach ($list_j_news as $row) {
                $arr_list_j_news[] = $row->id;
            }
        }
        $numberPage = $this->request->getQuery("page", "int");
        $paginator = new Paginator(["data" => $list, "limit" => 10, "page" => $numberPage]);
        $page_current = ($numberPage > 1) ? $numberPage : 1;
        $breadcrumb = '<li class="active">' . $this->view->module_name . '</li>';
        $this->view->arr_list_j_category = $arr_list_j_category;
        $this->view->arr_list_j_news = $arr_list_j_news;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
    }
    public function createAction()
    {
        $random = new Random();
        if ($this->cookies->has('row_id_news_type_' . $this->_get_subdomainID())) {
            // Get the cookie
            $rowIdCookie = $this->cookies->get('row_id_news_type_' . $this->_get_subdomainID());

            // Get the cookie's value
            $row_id = $rowIdCookie->getValue();
        } else {
            $row_id = $random->hex(10);
            $this->cookies->set(
                'row_id_news_type_' . $this->_get_subdomainID(),
                $row_id,
                time() + ROW_ID_COOKIE_TIME
            );
        }

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news_type/'. $row_id;
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
        $form = new NewsTypeForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new NewsType();
            $general = new General();
            $data = ['subdomain_id' => $this->_get_subdomainID(), 'name' => $this->request->getPost('name'), 'slug' => $this->request->getPost('slug'), 'title' => $this->request->getPost('title'), 'keywords' => $this->request->getPost('keywords'), 'description' => $this->request->getPost('description'), 'content' => str_replace("public/files/", "files/", $this->request->getPost('content')), 'sort' => $this->request->getPost('sort'), 'static' => 'N', 'active' => $this->request->getPost('active'), 'row_id' => $this->request->getPost('row_id'), ];
            $item->assign($data);
            if ($item->save()) {
                
                $this->cookies->get('row_id_news_type_' . $this->_get_subdomainID())->delete();
                $id = $item->id;
                $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
                if (count($menu) > 0) {
                    foreach ($menu as $row) {
                        $menu_item_list = MenuItem::findFirst(['menu_id' => $row->id, 'order' => 'sort DESC']);
                        $sort = (count($menu_item_list) > 0) ? $menu_item_list->sort + 1 : 1;
                        $menuItem = new MenuItem();
                        $data = ['subdomain_id' => $this->_get_subdomainID(), 'menu_id' => $row->id, 'parent_id' => 0, 'module_id' => $id, 'level' => 0, 'module_name' => 'news_type', 'name' => $this->request->getPost('name'), 'url' => $this->request->getPost('slug'), 'active' => 'Y', 'sort' => $sort];
                        $menuItem->assign($data);
                        $menuItem->save();
                    }
                }
                $this->flashSession->success($this->_message["add"]);
                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName();
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;
                }
                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }
        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="' . HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">' . $this->view->module_name . '</a></li><li class="active">' . $this->view->title_bar . '</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        // $this->view->type = $type;
        $this->view->row_id = $row_id;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->pick($this->_getControllerName() . '/form');
    }
    public function updateAction(int $id, int $page = 1)
    {
        $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $row_id = ($item->row_id != 0) ? $item->row_id : $item->id;

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news_type/'. $row_id;
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
        $form = new NewsTypeForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $general = new General();
            $slug = $this->request->getPost('slug');
            $data = ['name' => $this->request->getPost('name'), 'slug' => $this->request->getPost('slug'), 'title' => $this->request->getPost('title'), 'keywords' => $this->request->getPost('keywords'), 'description' => $this->request->getPost('description'), 'content' => str_replace("public/files/", "files/", $this->request->getPost('content')), 'sort' => $this->request->getPost('sort'), 'static' => 'N', 'active' => $this->request->getPost('active'), 'row_id' => $this->request->getPost('row_id'), ];
            $item->assign($data);
            if ($item->save()) {
                
                $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
                if (!empty($menu)) {
                    foreach ($menu as $row) {
                        $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'Y' AND module_name = 'news_type'", ]);
                        if (!empty($menuItem)) {
                            $menuItem->assign(['name' => $this->request->getPost('name'), 'url' => $slug]);
                            $menuItem->save();
                        }
                    }
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
        $breadcrumb = '<li><a href="' . HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">' . $this->view->module_name . '</a></li><li class="active">' . $this->view->title_bar . '</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->news_type_id = $id;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->row_id = $row_id;
        $this->view->pick($this->_getControllerName() . '/form');
    }
    public function createStaticAction()
    {
        $random = new Random();
        if ($this->cookies->has('row_id_news_type_static_' . $this->_get_subdomainID())) {
            // Get the cookie
            $rowIdCookie = $this->cookies->get('row_id_news_type_static_' . $this->_get_subdomainID());

            // Get the cookie's value
            $row_id = $rowIdCookie->getValue();
        } else {
            $row_id = $random->hex(10);
            $this->cookies->set(
                'row_id_news_type_static_' . $this->_get_subdomainID(),
                $row_id,
                time() + ROW_ID_COOKIE_TIME
            );
        }

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news_type/'. $row_id;
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
        $form = new NewsTypeForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new NewsType();
            $general = new General();
            $data = ['subdomain_id' => $this->_get_subdomainID(), 'name' => $this->request->getPost('name'), 'slug' => $this->request->getPost('slug'), 'title' => $this->request->getPost('title'), 'keywords' => $this->request->getPost('keywords'), 'description' => $this->request->getPost('description'), 'content' => str_replace("public/files/", "files/", $this->request->getPost('content')), 'sort' => $this->request->getPost('sort'), 'static' => 'Y', 'active' => $this->request->getPost('active'), 'row_id' => $this->request->getPost('row_id'), ];
            $item->assign($data);
            if ($item->save()) {
                
                $this->cookies->get('row_id_news_type_static_' . $this->_get_subdomainID())->delete();
                $id = $item->id;
                $this->flashSession->success($this->_message["add"]);
                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/createStatic';
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/static';
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/updateStatic/' . $id;
                }
                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }
        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="' . HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '/static' . '">' . $this->view->module_name . '</a></li><li class="active">' . $this->view->title_bar . '</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->row_id = $row_id;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->pick($this->_getControllerName() . '/form');
    }
    public function updateStaticAction(int $id, int $page = 1)
    {
        $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $row_id = ($item->row_id != 0) ? $item->row_id : $item->id;

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news_type/'. $row_id;
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
        $form = new NewsTypeForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $general = new General();
            $slug = $this->request->getPost('slug');
            $data = ['name' => $this->request->getPost('name'), 'slug' => $this->request->getPost('slug'), 'title' => $this->request->getPost('title'), 'keywords' => $this->request->getPost('keywords'), 'description' => $this->request->getPost('description'), 'content' => str_replace("public/files/", "files/", $this->request->getPost('content')), 'sort' => $this->request->getPost('sort'), 'static' => 'Y', 'active' => $this->request->getPost('active'), 'row_id' => $this->request->getPost('row_id'), ];
            $item->assign($data);
            if ($item->save()) {
                
                $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
                if (!empty($menu)) {
                    foreach ($menu as $row) {
                        $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'Y' AND module_name = 'news_type'", ]);
                        if (!empty($menuItem)) {
                            $menuItem->assign(['name' => $this->request->getPost('name'), 'url' => $slug]);
                            $menuItem->save();
                        }
                    }
                }
                $this->flashSession->success($this->_message["edit"]);
                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/createStatic';
                } elseif (!empty($save_close)) {
                    $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/static' . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/static';
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/updateStatic/' . $id . '/' . $page;
                }
                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }
        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="' . HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '/static' . '">' . $this->view->module_name . '</a></li><li class="active">' . $this->view->title_bar . '</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->news_type_id = $id;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->row_id = $row_id;
        $this->view->pick($this->_getControllerName() . '/form');
    }
    public function showAction(int $id, int $page = 1)
    {
        $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }
        $item->assign(['active' => 'Y', ]);
        if ($item->save()) {
            
            $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
            if (!empty($menu)) {
                foreach ($menu as $row) {
                    $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'Y' AND module_name = 'news_type'", ]);
                    if (!empty($menuItem)) {
                        $menuItem->assign(['active' => 'Y']);
                        $menuItem->save();
                    }
                }
            }
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }
    public function hideAction(int $id, int $page = 1)
    {
        $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        ;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }
        $item->assign(['active' => 'N']);
        if ($item->save()) {
            
            $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
            if (!empty($menu)) {
                foreach ($menu as $row) {
                    $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'N' AND module_name = 'news_type'", ]);
                    if (!empty($menuItem)) {
                        $menuItem->assign(['active' => 'Y']);
                        $menuItem->save();
                    }
                }
            }
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
            $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
            if ($item) {
                $item->assign(['active' => 'Y']);
                if ($item->save()) {
                    
                    $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
                    if (!empty($menu)) {
                        foreach ($menu as $row) {
                            $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'Y' AND module_name = 'news_type'", ]);
                            if (!empty($menuItem)) {
                                $menuItem->assign(['active' => 'Y']);
                                $menuItem->save();
                            }
                        }
                    }
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
            $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
            if ($item) {
                $item->assign(['active' => 'N']);
                if ($item->save()) {
                    
                    $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
                    if (!empty($menu)) {
                        foreach ($menu as $row) {
                            $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'Y' AND module_name = 'news_type'", ]);
                            if (!empty($menuItem)) {
                                $menuItem->assign(['active' => 'N']);
                                $menuItem->save();
                            }
                        }
                    }
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
    public function showmenuAction(int $id, int $page = 1)
    {
        $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }
        $item->assign(['menu' => 'Y']);
        if ($item->save()) {
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }
    public function hidemenuAction(int $id, int $page = 1)
    {
        $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }
        $item->assign(['menu' => 'N']);
        if ($item->save()) {
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }
    public function showStaticAction(int $id, int $page = 1)
    {
        $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/static' . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/static';
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }
        $item->assign(['active' => 'Y', ]);
        if ($item->save()) {
            $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
            if (!empty($menu)) {
                foreach ($menu as $row) {
                    $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'Y' AND module_name = 'news_type'", ]);
                    if (!empty($menuItem)) {
                        $menuItem->assign(['active' => 'Y']);
                        $menuItem->save();
                    }
                }
            }
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }
    public function hideStaticAction(int $id, int $page = 1)
    {
        $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        ;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/static' . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/static';
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }
        $item->assign(['active' => 'N']);
        if ($item->save()) {
            $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
            if (!empty($menu)) {
                foreach ($menu as $row) {
                    $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'N' AND module_name = 'news_type'", ]);
                    if (!empty($menuItem)) {
                        $menuItem->assign(['active' => 'Y']);
                        $menuItem->save();
                    }
                }
            }
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }
    /*public function showstaticAction(int $id, int $page = 1)

    {

        $item = NewsType::findFirst([

            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"

        ]);

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if (!$item) {

            $this->flashSession->error("Không tìm thấy dữ liệu");

            $this->response->redirect($url);

        }



        $item->assign([

            'static' => 'Y'

        ]);



        if ($item->save()) {

            $this->flashSession->success('Hiển thị dữ liệu thành công!');

            $this->response->redirect($url);

        }



    }



    public function hidestaticAction(int $id, $page = 1)

    {

        $item = NewsType::findFirst([

            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"

        ]);

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if (!$item) {

            $this->flashSession->error("Không tìm thấy dữ liệu");

            $this->response->redirect($url);

        }



        $item->assign([

            'static' => 'N'

        ]);



        if ($item->save()) {

            $this->flashSession->success('Hiển thị dữ liệu thành công!');

            $this->response->redirect($url);

        }



    }*/
    public function deleteAction(int $id, $page = 1)
    {
        $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $count_category = $this->count_category($id);
        $count_news = $this->count_news($id);
        if ($count_category != 0 or $count_news != 0) {
            $this->flashSession->error("Không thể xóa mục này vì chứa liên kết dữ liệu.");
            return $this->response->redirect($url);
        }
        $item->assign(['deleted' => 'Y']);
        if ($item->save()) {
            
            $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
            if (!empty($menu)) {
                foreach ($menu as $row) {
                    $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'Y' AND module_name = 'news_type'", ]);
                    if (!empty($menuItem)) {
                        $menuItem->assign(['deleted' => 'N']);
                        $menuItem->save();
                    }
                }
            }
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error($item->getMessages());
        }
        $this->response->redirect($url);
    }
    public function deleteStaticAction(int $id, $page = 1)
    {
        $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/static' . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/static';
        $count_category = $this->count_category($id);
        $count_news = $this->count_news($id);
        if ($count_category != 0 or $count_news != 0) {
            $this->flashSession->error("Không thể xóa mục này vì chứa liên kết dữ liệu.");
            return $this->response->redirect($url);
        }
        $item->assign(['deleted' => 'Y']);
        if ($item->save()) {
            
            $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
            if (!empty($menu)) {
                foreach ($menu as $row) {
                    $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'Y' AND module_name = 'news_type'", ]);
                    if (!empty($menuItem)) {
                        $menuItem->assign(['deleted' => 'N']);
                        $menuItem->save();
                    }
                }
            }
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
            $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
            $count_category = $this->count_category($id);
            $count_news = $this->count_news($id);
            if ($count_category != 0 or $count_news != 0) {
                $this->flashSession->error("Không thể xóa mục này vì chứa liên kết dữ liệu.");
                return $this->response->redirect($url);
            }
            if ($item) {
                $item->assign(['active' => 'N']);
                if ($item->save()) {
                    
                    $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
                    if (!empty($menu)) {
                        foreach ($menu as $row) {
                            $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'Y' AND module_name = 'news_type'", ]);
                            if (!empty($menuItem)) {
                                $menuItem->assign(['deleted' => 'N']);
                                $menuItem->save();
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
    public function _deleteAction(int $id, $page = 1)
    {
        $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $general = new General();
        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            
            $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
            if (!empty($menu)) {
                foreach ($menu as $row) {
                    $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'Y' AND module_name = 'news_type'", ]);
                    if (!empty($menuItem)) {
                        $menuItem->delete();
                    }
                }
            }
            if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news_type/" . $item->row_id)) {
                $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news_type/" . $item->row_id);
            }
            $this->flashSession->success($this->_message["delete"]);
        }
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }
    public function _deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);
        $general = new General();
        $d = 0;
        foreach ($listid as $id) {
            $item = NewsType::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
            if ($item) {
                if ($item->delete()) {
                    
                    $menu = Menu::find(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND active = 'Y' AND main = 'Y'"]);
                    if (!empty($menu)) {
                        foreach ($menu as $row) {
                            $menuItem = MenuItem::findFirst(["conditions" => "menu_id = " . $row->id . " AND active = 'Y' AND module_name = 'news_type'", ]);
                            if (!empty($menuItem)) {
                                $menuItem->delete();
                            }
                        }
                        if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news_type/" . $item->row_id)) {
                            $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news_type/" . $item->row_id);
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

    public function count_category(int $id)
    {
        $result = $this->modelsManager->createBuilder()->columns(array('count' => 'COUNT(*)'))->from(['nc' => '\Modules\Models\NewsCategory'])->join('Modules\Models\NewsType', 'nt.id = nc.type_id', 'nt')->where('nt.id = ' . $id . ' AND nc.deleted = "N"')->getQuery()->execute();
        return $result[0]['count'];
    }

    public function count_news(int $id)
    {
        $result = $this->modelsManager->createBuilder()->columns(array('count' => 'COUNT(*)'))->from(['n' => '\Modules\Models\News'])->join('Modules\Models\NewsType', 'nt.id = n.type_id', 'nt')->where('nt.id = ' . $id . ' AND n.deleted = "N"')->getQuery()->execute();
        return $result[0]['count'];
    }

    private function deleteCache()
    {
        
    }
}
