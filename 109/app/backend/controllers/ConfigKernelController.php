<?php

namespace Modules\Backend\Controllers;

use Modules\Models\ConfigKernel;
use Modules\Models\ConfigItem;
use Modules\Models\Subdomain;
use Modules\Models\WordCore;
use Modules\Models\WordItem;
use Modules\Forms\ConfigKernelForm;
use Modules\Forms\WordCoreForm;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Text;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;

class ConfigKernelController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Cấu hình toàn hệ thống';
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
        $list = ConfigKernel::find(
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
        $form = new ConfigKernelForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');
            $item = new ConfigKernel();

            $data = [
                'name' => $this->request->getPost('name'),
                'field' => $this->request->getPost('field'),
                'value' => $this->request->getPost('value'),
                'description' => $this->request->getPost('description'),
                'place_holder' => $this->request->getPost('place_holder'),
                'type' => $this->request->getPost('type'),
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
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction($id, $page = 1)
    {
        $item = ConfigKernel::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $form = new ConfigKernelForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');

            $data = [
                'name' => $this->request->getPost('name'),
                'field' => $this->request->getPost('field'),
                'value' => $this->request->getPost('value'),
                'description' => $this->request->getPost('description'),
                'place_holder' => $this->request->getPost('place_holder'),
                'type' => $this->request->getPost('type'),
                'sort' => $this->request->getPost('sort'),
                'active' => $this->request->getPost('active')
            ];

            $item->assign($data);

            if ($item->save()) {
                

                $this->flashSession->success("Cập nhật dữ liệu thành công");
                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName() . '/index/' . $type;
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
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->item = $item;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function configAction()
    {
        $list_config_kernel = ConfigKernel::find([
            "conditions" => "active='Y'",
            "order" => "sort ASC, name ASC, id DESC"
        ]);

        $list_config_kernel_arr = array();
        if (count($list_config_kernel)) {
            $list_config_kernel_arr = $list_config_kernel->toArray();
            for ($i=0; $i < count($list_config_kernel_arr); $i++) {
                if ($list_config_kernel_arr[$i]['type'] == 'radio' || $list_config_kernel_arr[$i]['type'] == 'select' || $list_config_kernel_arr[$i]['type'] == 'checkbox') {
                    $list_config_kernel_arr[$i]['list_value'] = json_decode($list_config_kernel_arr[$i]['value'], true);
                }
            }
        }

        if ($this->request->isPost()) {
            $request = $this->request->getPost();
            if (!empty($list_config_kernel_arr)) {
                foreach ($list_config_kernel_arr as $row) {
                    if (isset($request[$row['field']])) {
                        $config_kernel = ConfigKernel::findFirstById($row['id']);
                        if ($row['type'] == 'text' || $row['type'] == 'textarea') {
                            $config_kernel->assign([
                                'value' => $request[$row['field']]
                            ]);
                            $config_kernel->save();
                        }
                        if ($row['type'] == 'checkbox' || $row['type'] == 'radio') {
                            $array_list_value = array();
                            foreach ($row['list_value'] as $key => $value) {
                                $array_list_value[] = $value;
                                if (in_array($value['name'], $request[$row['field']])) {
                                    $array_list_value[$key]['select'] = 1;
                                }
                                if (!in_array($value['name'], $request[$row['field']])) {
                                    $array_list_value[$key]['select'] = 0;
                                }
                            }
                            $config_kernel->assign([
                                'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                            ]);
                            $config_kernel->save();
                        }
                        if ($row['type'] == 'select') {
                            $array_list_value = array();
                            foreach ($row['list_value'] as $key => $value) {
                                $array_list_value[] = $value;
                                if ($value['name'] == $request[$row['field']]) {
                                    $array_list_value[$key]['select'] = 1;
                                } else {
                                    $array_list_value[$key]['select'] = 0;
                                }
                            }
                            $config_kernel->assign([
                                'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                            ]);
                            $config_kernel->save();
                        }
                    }
                }
            }

            $this->flashSession->success($this->_message["edit"]);
            $url = ACP_NAME . '/' . $this->_getControllerName() . '/config';
            $this->response->redirect($url);
        }

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->list_config_kernel_arr = $list_config_kernel_arr;
    }

    public function showAction($id, $page = 1)
    {
        $item = ConfigKernel::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            
            $this->flashSession->success("Hiển thị dữ liệu thành công!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function hideAction($id, $page = 1)
    {
        $item = ConfigKernel::findFirstById($id);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            
            $this->flashSession->success("Ẩn dữ liệu thành công!");
        }

        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;

        $this->response->redirect($url);
    }

    public function showmultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = ConfigKernel::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'Y',
                ));
                $item->save();
                $d++;
            }
        }

        if ($d > 0) {
            
            $this->flashSession->success("Thao tác thành công!");
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
            $item = ConfigKernel::findFirstById($id);
            if ($item) {
                $item->assign(array(
                    'active' => 'N',
                ));
                $item->save();
                $d++;
            }
        }

        if ($d > 0) {
            
            $this->flash->success("Ẩn dữ liệu thành công");
        } else {
            $this->flash->error("Không tìm thấy dữ liệu");
        }
        $controllerName = $this->_getControllerName();
        $url = ($page > 1) ? ACP_NAME . '/' . $controllerName . '?page=' . $page : ACP_NAME . '/' . $controllerName;
        $this->response->redirect($url);
    }

    public function deleteAction($id, $page = 1)
    {
        $item = ConfigKernel::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'deleted' => 'Y',
        ));

        if ($item->save()) {
            
            $this->flashSession->success("Xóa dữ liệu thành công");
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
            $item = ConfigKernel::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                $item->assign(array(
                    'deleted' => 'N',
                ));
                $item->save();
                $d++;
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

    public function _deleteAction($id, $page = 1)
    {
        $item = ConfigKernel::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $photo = $item->photo;
        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            
            $this->flashSession->success("Xóa dữ liệu thành công");
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $this->response->redirect($url);
    }

    public function _deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = ConfigKernel::findFirst([
                "conditions" => "id = $id"
            ]);
            if ($item) {
                if (!$item->delete()) {
                    $this->flashSession->error($item->getMessages());
                } else {
                    
                }
                $d++;
            }
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        if ($d > 0) {
            
            $this->flashSession->success("Xóa dữ liệu thành công");
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }
}
