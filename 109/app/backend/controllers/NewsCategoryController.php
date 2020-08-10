<?php

namespace Modules\Backend\Controllers;

use Modules\Models\NewsCategory;
use Modules\Models\TmpNewsNewsCategory;
use Modules\Forms\NewsCategoryForm;
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

class NewsCategoryController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Danh mục tin tức';
    }

    /**
     * Default action, shows the search form
     */
    public function indexAction(int $type = 0)
    {
        $items = $this->recursive(0, $type);
        $page_current = 1;

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->items = $items;
        $this->view->page_current = $page_current;
        $this->view->type = $type;
    }


    /**
     * Creates a User
     *
     */
    public function createAction(int $type = 0)
    {
        $random = new Random();
        if ($this->cookies->has('row_id_news_category_' . $this->_get_subdomainID())) {
            // Get the cookie
            $rowIdCookie = $this->cookies->get('row_id_news_category_' . $this->_get_subdomainID());

            // Get the cookie's value
            $row_id = $rowIdCookie->getValue();
        } else {
            $row_id = $random->hex(10);
            $this->cookies->set(
                'row_id_news_category_' . $this->_get_subdomainID(),
                $row_id,
                time() + ROW_ID_COOKIE_TIME
            );
        }

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news_category/'. $row_id;
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
        $form = new NewsCategoryForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new NewsCategory();
            $general = new General();
            $slug = $this->request->getPost('slug');

            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'type_id' => $type,
                'parent_id' => $this->request->getPost('parent_id'),
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'content' => str_replace("public/files/", "files/", $this->request->getPost('content')),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
                'row_id' => $this->request->getPost('row_id'),
            ];

            if ($data['parent_id'] != 0) {
                $item_parent = NewsCategory::findFirst(
                    [
                        'columns' => 'level',
                        'conditions' => 'id = '. $data['parent_id'] .''
                    ]
                );
                $data['level'] = $item_parent->level + 1;
            }

            $item->assign($data);

            if ($item->save()) {
                
                $this->cookies->get('row_id_news_category_' . $this->_get_subdomainID())->delete();
                $id = $item->id;
                $this->flashSession->success($this->_message["add"]);

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create/' . $type;
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $type . '/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $list = $this->recursive(0, $type);

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->list = $list;
        $this->view->type = $type;
        $this->view->row_id = $row_id;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    /**
     * Saves the user from the 'edit' action
     *
     */
    public function updateAction(int $type = 0, int $id, int $page = 1)
    {
        $item = NewsCategory::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND type_id = $type"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $row_id = ($item->row_id != 0) ? $item->row_id : $item->id;

        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/news_category/'. $row_id;
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
        $form = new NewsCategoryForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $general = new General();
            $slug = $this->request->getPost('slug');

            $data = [
                'parent_id' => $this->request->getPost('parent_id'),
                'name' => $this->request->getPost('name'),
                'slug' => $slug,
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'content' => str_replace("public/files/", "files/", $this->request->getPost('content')),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active'),
                'row_id' => $this->request->getPost('row_id'),
            ];

            $data['level'] = 0;
            if ($data['parent_id'] == 0) {
                $data['level'] = 0;
                if ($item->level != 0) {
                    $itemChilds = NewsCategory::find([
                        'columns' => 'id, level',
                        'conditions' => 'parent_id = '. $item->id .''
                    ]);
                    if (count($itemChilds) > 0) {
                        foreach ($itemChilds as $itemChild) {
                            $level = $itemChild->level - $item->level;
                            $itemChildRow = NewsCategory::findFirstById($itemChild->id);
                            $itemChildRow->assign(['level' => $level]);
                            $itemChildRow->save();
                        }
                    }
                }
            } else {
                $item_parent = NewsCategory::findFirst(
                    [
                        'columns' => 'level',
                        'conditions' => 'id = '. $data['parent_id'] .''
                    ]
                );
                $data['level'] = $item_parent->level + 1;

                if ($item->level == 0) {
                    $itemChilds = NewsCategory::find([
                        'columns' => 'id, level',
                        'conditions' => 'parent_id = '. $item->id .''
                    ]);
                    if (count($itemChilds) > 0) {
                        foreach ($itemChilds as $itemChild) {
                            $level = $itemChild->level + $item_parent->level + 1;
                            $itemChildRow = NewsCategory::findFirstById($itemChild->id);
                            $itemChildRow->assign(['level' => $level]);
                            $itemChildRow->save();
                        }
                    }
                }
            }

            $item->assign($data);

            if ($item->save()) {
                
                $this->flashSession->success($this->_message["edit"]);
                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create/' . $type;
                } elseif (!empty($save_close)) {
                    $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $type . '/' . $id . '/' . $page;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $list = $this->recursive(0, $type);

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->list = $list;
        $this->view->item = $item;
        $this->view->type = $type;
        $this->view->img_upload_paths = $imgUploadPaths;
        $this->view->row_id = $row_id;
        $this->view->pick($this->_getControllerName() . '/form');
    }


    public function showAction(int $type, int $id, int $page = 1)
    {
        $item = NewsCategory::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND type_id = $type"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'Y',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function hideAction(int $type, int $id, int $page = 1)
    {
        $item = NewsCategory::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND type_id = $type"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'N'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function showmultyAction(int $type, int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = NewsCategory::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND type_id = $type"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'Y'
                ]);
                $item->save();
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

        if ($d > 0) {
            
            $this->flashSession->success($this->_message["show"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function hidemultyAction(int $type, int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = NewsCategory::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND type_id = $type"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'N'
                ]);
                $item->save();
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

        if ($d > 0) {
            
            $this->flashSession->success($this->_message["hide"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function showmenuAction(int $type, int $id, int $page = 1)
    {
        $item = NewsCategory::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND type_id = $type"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'menu' => 'Y'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function hidemenuAction(int $type, int $id, int $page = 1)
    {
        $item = NewsCategory::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND type_id = $type"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'menu' => 'N'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function deleteAction(int $type, int $id, $page = 1)
    {
        $item = NewsCategory::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND type_id = $type"
        ]);
        if (!$item) {
            $this->flashSession->success($this->_message["delete"]);
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;

        $count_child = $this->count_child($id);
        $count_news = $this->count_news($id);

        if ($count_child != 0 or $count_news != 0) {
            $this->flashSession->error("Không thể xóa mục này vì chứa liên kết dữ liệu.");
            return $this->response->redirect($url);
        }

        $item->assign([
            'deleted' => 'Y'
        ]);

        if ($item->save()) {
            
            $this->flashSession->success("Xóa dữ liệu thành công");
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $this->response->redirect($url);
    }

    public function deletemultyAction(int $type, int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        foreach ($listid as $id) {
            $item = NewsCategory::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND type_id = $type"
            ]);

            $count_child = $this->count_child($id);
            $count_news = $this->count_news($id);

            if ($count_child != 0 or $count_news != 0) {
                $this->flashSession->error("Không thể xóa mục này vì chứa liên kết dữ liệu.");
                return $this->response->redirect($url);
            }

            if ($item) {
                $item->assign([
                    'deleted' => 'Y'
                ]);
                $item->save();
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

    public function _deleteAction(int $type, int $id, $page = 1)
    {
        $item = NewsCategory::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id AND type_id = $type"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $general = new General();
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        $categoryChild = NewsCategory::find(['conditions' => 'parent_id = '. $id .'']);
        if (count($categoryChild) > 0) {
            $this->flashSession->error('Bạn phải xóa danh mục con trước khi xóa danh mục cha');
            return $this->response->redirect($url);
        }
        if (!$item->delete()) {
            foreach ($item->getMessages() as $message) {
                $this->flashSession->error($message);
            }
        } else {
            
            TmpNewsNewsCategory::deleteByRawSql('news_category_id ='. $id .'');
            if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news_category/" . $item->row_id)) {
                $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news_category/" . $item->row_id);
            }
            $this->flashSession->success($this->_message["delete"]);
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
        $this->response->redirect($url);
    }

    public function _deletemultyAction(int $type, int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);
        $general = new General();
        $d = 0;
        foreach ($listid as $id) {
            $item = NewsCategory::findFirstById($id);
            if ($item) {
                $categoryChild = NewsCategory::find(['conditions' => 'parent_id = '. $id .'']);
                if (count($categoryChild) > 0) {
                    $this->flashSession->error('Bạn phải xóa danh mục con trước khi xóa danh mục cha');
                    return $this->response->redirect($url);
                }
                if ($item->delete()) {
                    TmpNewsNewsCategory::deleteByRawSql('news_category_id ='. $id .'');
                    foreach ($item->getMessages() as $message) {
                        $this->flashSession->error($message);
                    }
                    if (is_dir("uploads/" . $this->_get_subdomainFolder() . "/news_category/" . $item->row_id)) {
                        $general->deleteDirectory("uploads/" . $this->_get_subdomainFolder() . "/news_category/" . $item->row_id);
                    }
                    $d++;
                } else {
                    foreach ($item->getMessages() as $message) {
                        $this->flashSession->error($message);
                    }
                }
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

    public function updateSubdomainIdAction()
    {
        $tmpNewsNewsCategories = TmpNewsNewsCategory::findBySubdomainId(0);
        foreach ($tmpNewsNewsCategories as $tmpNewsNewsCategory) {
            if ($tmpNewsNewsCategory->news) {
                $tmpNewsNewsCategory->subdomain_id = $tmpNewsNewsCategory->news->subdomain_id;
                $tmpNewsNewsCategory->save();
            }
        }
    }

    public function recursive($parent_id = 0, $type = 0, $space = "", $trees = array())
    {
        if (!$trees) {
            $trees = [];
        }
        $result = NewsCategory::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "parent_id = ". $parent_id ." AND type_id = $type AND subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
            ]
        );

        $trees_obj = array();
        if (!empty($result)) {
            foreach ($result as $row) {
                $trees[] = [
                    'id' => $row->id,
                    'parent_id' => $row->parent_id,
                    'level' => $row->level,
                    'name' => $space . $row->name,
                    'slug' => $row->slug,
                    'hits' => $row->hits,
                    'sort' => $row->sort,
                    'active' => $row->active,
                    'menu' => $row->menu,
                    "count_child" => $this->count_child($row->id),
                    "count_news" => $this->count_news($row->id)
                ];
                $trees   = $this->recursive($row->id, $type, $space . '|---', $trees);
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
            ->from(['n' => '\Modules\Models\NewsCategory'])
            ->where('n.parent_id = '. $id .' AND n.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }

    public function count_news(int $id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['n' => '\Modules\Models\News'])
            ->join('Modules\Models\TmpNewsNewsCategory', 'tmp.news_id = n.id', 'tmp')
            ->where('tmp.news_category_id = '. $id .' AND n.deleted = "N"')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }

    private function deleteCache()
    {
        
    }
}
