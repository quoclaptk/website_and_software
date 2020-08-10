<?php

namespace Modules\Backend\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Modules\Models\CustomerComment;
use Modules\Forms\CustomerCommentForm;

class CustomerCommentController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Ý kiến khách hàng';
        $this->assets->addJs("assets/js/ajaxupload.js");
    }

    public function indexAction()
    {
        $list = CustomerComment::find(
            array(
                "order" => "id DESC",
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
            )
        );

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            array(
                "data" => $list,
                "limit" => 20,
                "page" => $numberPage
            )

        );

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
    }

    public function createAction()
    {
        $form = new CustomerCommentForm();
        $this->view->title_bar = 'Thêm mới';
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');

            $customerComment = new CustomerComment();

            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'name' => ($this->request->getPost('name')) ? $this->request->getPost('name', array('striptags', 'string')) : '',
                'phone' => ($this->request->getPost('phone')) ? $this->request->getPost('phone') : '',
                'email' => ($this->request->getPost('email')) ? $this->request->getPost('email') : '',
                'photo' => ($this->request->getPost('cc_f_photo') != '') ? $this->request->getPost('cc_f_photo') : '',
                'address' => ($this->request->getPost('address')) ? $this->request->getPost('address', array('striptags', 'string')) : '',
                'comment' => ($this->request->getPost('comment')) ? $this->request->getPost('comment', array('striptags', 'string')) : '',
                'active' => $this->request->getPost('active')
            ];

            $customerComment->assign($data);
            if ($customerComment->save()) {
                $id = $customerComment->id;
                $this->flashSession->success($this->_message["add"]);

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/orders?active=customer_comment';
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($customerComment->getMessages());
            }
        }

        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->folder = $this->_get_subdomainFolder();
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function updateAction($id)
    {
        $item = CustomerComment::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $form = new CustomerCommentForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_new = $this->request->getPost('save_new');
            $save_close = $this->request->getPost('save_close');

            $data = [
                'subdomain_id' => $this->_get_subdomainID(),
                'name' => ($this->request->getPost('name')) ? $this->request->getPost('name', array('striptags', 'string')) : '',
                'phone' => ($this->request->getPost('phone')) ? $this->request->getPost('phone', 'int') : '',
                'email' => ($this->request->getPost('email')) ? $this->request->getPost('email') : '',
                'photo' => ($this->request->getPost('cc_f_photo') != '') ? $this->request->getPost('cc_f_photo') : '',
                'address' => ($this->request->getPost('address')) ? $this->request->getPost('address', array('striptags', 'string')) : '',
                'comment' => ($this->request->getPost('comment')) ? $this->request->getPost('comment', array('striptags', 'string')) : '',
                'active' => $this->request->getPost('active')
            ];

            $item->assign($data);
            if ($item->save()) {
                $this->flashSession->success($this->_message["add"]);

                if (!empty($save_new)) {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/create';
                } elseif (!empty($save_close)) {
                    $url = ACP_NAME . '/orders?active=customer_comment';
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Cập nhật';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->item = $item;
        $this->view->form = $form;
        $this->view->folder = $this->_get_subdomainFolder();
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function showAction($id, $page = 1)
    {
        $item = CustomerComment::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders?active=customer_comment';
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'Y',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["show"]);
            $this->response->redirect($url);
        }
    }

    public function hideAction($id, $page = 1)
    {
        $item = CustomerComment::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        // $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders?active=customer_comment';
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect($url);
        }

        $item->assign(array(
            'active' => 'N',
        ));

        if ($item->save()) {
            
            $this->flashSession->success($this->_message["hide"]);
            $this->response->redirect($url);
        }
    }

    public function deleteAction($id, $page = 1)
    {
        $item = CustomerComment::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $item->assign(array(
            'deleted' => 'Y',
        ));

        if ($item->save()) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error($item->getMessages());
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders?active=customer_comment';
        $this->response->redirect($url);
    }

    public function deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = CustomerComment::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            if ($item) {
                $item->assign(array(
                    'deleted' => 'Y',
                ));
                $item->save();
                $d++;
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders?active=customer_comment';
        if ($d > 0) {
            $this->flashSession->success($this->_message["delete"]);
            
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function _deleteAction($id, $page = 1)
    {
        $item = CustomerComment::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

        $photo = $item->photo;

        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            if ($photo != '') {
                @unlink(substr($photo, 1));
            }
            $this->flashSession->success($this->_message["delete"]);
        }

        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders?active=customer_comment';
        $this->response->redirect($url);
    }

    public function _deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = CustomerComment::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);

            $photo = $item->photo;

            if ($item->delete()) {
                if ($photo != '') {
                    @unlink($photo);
                }
                $d++;
            }
        }
        
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders?active=customer_comment';
        if ($d > 0) {
            
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }
}
