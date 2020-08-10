<?php

namespace Modules\Backend\Controllers;

use Modules\Models\ConfigGroup;
use Modules\Forms\ConfigGroupForm;
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
use Phalcon\Mvc\Dispatcher;

class ConfigGroupController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Nhóm cấu hình';
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $identity = $this->auth->getIdentity();
        if ($identity['role'] != 1) {
            $dispatcher->forward(array(
                'module' => 'backend',
                'controller' => 'index',
                'action' => 'noPermission'
            ));
            return false;
        }
    }

    public function indexAction()
    {
        $list = ConfigGroup::find(
            [
                "order" => "sort ASC, id DESC",
                "conditions" => "deleted = 'N'"
            ]
        );


        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            [
                "data" => $list,
                "limit" => 10,
                "page" => $numberPage
            ]
        );

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
    }

    public function createAction()
    {
        $form = new ConfigGroupForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new ConfigGroup();

            $data = [
                'name' => $this->request->getPost('name'),
                'module' => $this->request->getPost('module'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            ];

            $item->assign($data);

            if ($item->save()) {
                
                $id = $item->id;
                $this->flashSession->success("Thêm mới thành công");
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


        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction(int $id, int $page = 1)
    {
        $item = ConfigGroup::findFirst([
            "conditions" => "id = $id"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $form = new ConfigGroupForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $data = [
                'name' => $this->request->getPost('name'),
                'module' => $this->request->getPost('module'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            ];

            $item->assign($data);

            if ($item->save()) {
                
                $this->flashSession->success("Cập nhật dữ liệu thành công");
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
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function showAction(int $id, int $page = 1)
    {
        $item = ConfigGroup::findFirst([
            "conditions" => "id = $id"
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
            
            $this->flashSession->success('Hiển thị dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function hideAction(int $id, int $page = 1)
    {
        $item = ConfigGroup::findFirst([
            "conditions" => "id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign([
            'active' => 'N',
        ]);

        if ($item->save()) {
            
            $this->flashSession->success('Ẩn dữ liệu thành công!');
            $this->response->redirect($url);
        }
    }

    public function showmultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = ConfigGroup::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'Y'
                ]);
                if ($item->save()) {
                    $d++;
                }
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if ($d > 0) {
            
            $this->flashSession->success("Hiển thị dữ liệu thành công");
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
            $item = ConfigGroup::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                $item->assign([
                    'active' => 'N'
                ]);
                if ($item->save()) {
                    $d++;
                }
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

        if ($d > 0) {
            
            $this->flashSession->success("Ẩn dữ liệu thành công");
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function deleteAction(int $id, $page = 1)
    {
        $item = ConfigGroup::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $count_config_core = $this->count_config_core($id);
        if ($count_config_core > 0) {
            $this->flash->error("Không thể xóa vì chứa ràng buộc dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();

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

    public function deletemultyAction(int $page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        foreach ($listid as $id) {
            $item = ConfigGroup::findFirst([
                "conditions" => "id = $id"
            ]);

            $count_config_core = $this->count_config_core($id);
            if ($count_config_core > 0) {
                $this->flash->error("Không thể xóa vì chứa ràng buộc dữ liệu");
                return $this->dispatcher->forward(array('action' => 'index'));
            }

            if ($item) {
                $item->assign([
                    'deleted' => 'N'
                ]);
                if ($item->save()) {
                    $d++;
                }
            }
        }


        if ($d > 0) {
            
            $this->flashSession->success("Ẩn dữ liệu thành công");
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function _deleteAction(int $id, $page = 1)
    {
        $item = ConfigGroup::findFirst([
            "conditions" => "id = $id"
        ]);

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            $this->flashSession->success("Xóa dữ liệu thành công");
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
            $item = ConfigGroup::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                if ($item->delete()) {
                    $d++;
                }
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if ($d > 0) {
            
            $this->flashSession->success("Ẩn dữ liệu thành công");
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function count_config_core(int $id)
    {
        $result = $this->modelsManager->createBuilder()
            ->columns(array('count' => 'COUNT(*)'))
            ->from(['cc' => 'Modules\Models\ConfigCore'])
            ->where('cc.config_group_id = '. $id .'')
            ->getQuery()
            ->execute();
        return $result[0]['count'];
    }
}
