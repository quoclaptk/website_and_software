<?php

namespace Modules\Backend\Controllers;

use Modules\Models\DomainPointer;
use Modules\Forms\DomainPointerForm;
use Modules\Forms\DomainForm;
use Modules\PhalconVn\DirectAdmin;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use Phalcon\Mvc\Model\Resultset\Simple;
use Phalcon\Text as TextRandom;
use Phalcon\Image\Adapter\GD;

class DomainPointerController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Quản lý tên miền';
        $this->directAdmin = new DirectAdmin();
        $this->directAdmin->connect($this->config->directAdmin->hostname, $this->config->directAdmin->port);
        $this->directAdmin->set_login($this->config->directAdmin->username, $this->config->directAdmin->password);
        $this->directAdmin->set_method('get');
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
        $list = DomainPointer::find([
            "order" => "id DESC",
            "deleted = 'N'"
        ]);

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator([
            "data" => $list,
            "limit" => 50,
            "page" => $numberPage
        ]);

        $page_current = ($numberPage > 1) ? $numberPage : 1;

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
    }

    public function createAction()
    {
        $form = new DomainPointerForm();
        $url = ACP_NAME . '/' . $this->_getControllerName();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $domain =  $this->request->getPost("name");
            $domainPointer = new DomainPointer();
//            $da = new DirectAdmin();
            $this->directAdmin->query('/CMD_API_DOMAIN_POINTER', array(
                'domain' => $this->config->directAdmin->hostname,
                'action' => 'add',
                'from' => $domain,
                'alias' => 'yes',
            ));
            $result = $this->directAdmin->fetch_parsed_body();
            if (!isset($result['error']) || $result['error'] == '') {
                $this->flashSession->error("Đã có lỗi xảy ra với tên miền $domain. Vui lòng liên hệ hỗ trợ để được trợ giúp");
                return $this->response->redirect($url);
            } else {
                $domainPointer->assign([
                    "name" => $this->request->getPost("name")
                ]);
                $domainPointer->save();

                $this->flashSession->success("Trỏ tên miền $domain thành công.");
                return $this->response->redirect($url);
            }
        }

        $this->view->title_bar = 'Thêm mới';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->form = $form;
        $this->view->breadcrumb = $breadcrumb;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function editAction($id)
    {
        $this->view->setTemplateBefore('form');
        $item = DomainPointer::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect('/' . ACP_NAME);
        }

        $form = new DomainPointerForm($item, ['edit' => true]);
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $item->assign([
                'name' => $this->request->getPost('name')
            ]);
            if ($item->save()) {
                return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName() . '/' . $item->id . '?message=success');
            }
        }

        $this->view->title_bar = "Cập nhật domain";
        $this->view->item = $item;
        $this->view->form = $form;
        $this->view->message = $this->request->get('message');
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function deleteAction($id)
    {
        $item = DomainPointer::findFirst([
            "conditions" => "id = $id"
        ]);
        if (!$item) {
            $this->flashSession->error("Không tìm thấy dữ liệu");
            $this->response->redirect('/' . ACP_NAME . '/' . $this->_getControllerName());
        }
        $this->directAdmin->query('/CMD_API_DOMAIN_POINTER', array(
            'domain' => $this->config->directAdmin->hostname,
            'action' => 'delete',
            'select0' => $item->name
        ));
        $this->directAdmin->fetch_parsed_body();
        if ($item->delete()) {
            $this->flashSession->success("Xóa domain ". $item->name ." thành công");
            $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName());
        }
    }
}
