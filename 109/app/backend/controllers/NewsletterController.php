<?php
namespace Modules\Backend\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Modules\Models\Newsletter;

class NewsletterController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Liên hệ';
    }

    public function indexAction()
    {
        $list = Newsletter::find(array("order" => "id DESC", "conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND deleted = 'N'"));
        $numberPage = $this->request->getQuery("page", "int");
        $paginator = new Paginator(array("data" => $list, "limit" => 20, "page" => $numberPage));
        $page_current = ($numberPage > 1) ? $numberPage : 1;
        $breadcrumb = '<li class="active">' . $this->view->module_name . '</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
    }

    public function adsMailAction()
    {
        $breadcrumb = '<li class="active">Email quảng cáo</li>';
        $this->view->breadcrumb = $breadcrumb;
    }

    public function updateAction($id, $page = 1)
    {
        $item = Newsletter::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $this->view->title_bar = 'Nội dung liên hệ';
        $breadcrumb = '<li><a href="' . HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName() . '">' . $this->view->module_name . '</a></li><li class="active">' . $this->view->title_bar . '</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->item = $item;
    }

    public function deleteAction($id, $page = 1)
    {
        $item = Newsletter::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        $item->assign(array('deleted' => 'Y',));
        if ($item->save()) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error($item->getMessages());
        }
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders';
        $this->response->redirect($url);
    }

    public function deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);
        $d = 0;
        foreach ($listid as $id) {
            $item = Newsletter::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
            if ($item) {
                $item->assign(array('deleted' => 'Y',));
                $item->save();
                $d++;
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders';
        if ($d > 0) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function _deleteAction($id, $page = 1)
    {
        $item = Newsletter::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }
        if (!$item->delete()) {
            $this->flashSession->error($item->getMessages());
        } else {
            $this->flashSession->success($this->_message["delete"]);
        }
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders';
        $this->response->redirect($url);
    }

    public function _deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);
        $d = 0;
        foreach ($listid as $id) {
            $item = $item = Newsletter::findFirst(["conditions" => "subdomain_id = " . $this->_get_subdomainID() . " AND id = $id"]);
            if ($item->delete()) {
                $d++;
            }
        }
        //echo $d;die;
        $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
        $url = ACP_NAME . '/orders';
        if ($d > 0) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }
}
