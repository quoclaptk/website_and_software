<?php

namespace Modules\Backend\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Modules\Models\CustomerMessage;
use Modules\Models\Users;
use Modules\Models\Subdomain;
use Phalcon\Paginator\Adapter\QueryBuilder;
use Phalcon\Mvc\View;

class CustomerMessageController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Tin nhắn khách hàng';
    }

    public function indexAction()
    {
        $list = CustomerMessage::find(
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

    public function allItemsAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $this->view->setRenderLevel(
                View::LEVEL_ACTION_VIEW
            );
            $page = $this->request->getPost('page');
            $identity = $this->auth->getIdentity();
            $user = Users::findFirstBySubdomainId($this->_get_subdomainID());
            if ($user->role == 3) {
                $listSubName = $this->mainGlobal->getConfigKerNel('_cf_kernel_text_domain_view_order');
                if (!empty($listSubName)) {
                    $subs = explode(',', $listSubName);
                    $listId[] = $this->_get_subdomainID();
                    foreach ($subs as $sub) {
                        $subdomain = Subdomain::findFirstByName($sub);
                        if ($subdomain) {
                            $listId[] = $subdomain->id;
                        }
                    }

                    $listId = count($listId) > 0  ? implode(',', $listId) : $listId[0];

                    $subdomains = Subdomain::find([
                        'conditions' => 'id IN ('. $listId .')'
                    ]);

                    $list = $this->modelsManager->createBuilder()
                        ->from(MODELS . '\CustomerMessage')
                        ->where("subdomain_id IN ($listId) AND deleted = 'N'")
                        ->orderBy("id DESC");
                }
            } else {
                $list = $this->modelsManager->createBuilder()
                    ->from(MODELS . '\CustomerMessage')
                    ->where("subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'")
                    ->orderBy("id DESC");
            }

            $paginatorCustomerMessages = new QueryBuilder(
                [
                    "builder" => $list,
                    "limit"   => 50,
                    "page"    => $page,
                ]
            );

            $this->view->user = $user;
            $this->view->page_current = 1;
            $this->view->pageCustomerMessages = $paginatorCustomerMessages->getPaginate();
            $this->view->url_page = ACP_NAME . '/customer_message/allItems';
            $this->view->pick($this->_getControllerName() . '/allItems');
        }
    }

    public function updateAction($id, $page = 1)
    {
        $item = CustomerMessage::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $this->view->title_bar = 'Nội dung';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->item = $item;
    }

    public function deleteAction($id, $page = 1)
    {
        $item = CustomerMessage::findFirst([
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
        $url = ACP_NAME . '/orders';
        $this->response->redirect($url);
    }

    public function deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = CustomerMessage::findFirst([
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
        // check subdomain order
        $user = Users::findFirstBySubdomainId($this->_get_subdomainID());
        if ($user->role == 3) {
            if ($this->isNotDeleteOrder) {
                $this->flashSession->error("Tên miền này không được phép xóa");
                $url = ACP_NAME . '/orders?active=customer_message';
                return $this->response->redirect($url);
            }
        }
        
        $item = CustomerMessage::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);

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
        // check subdomain order
        $user = Users::findFirstBySubdomainId($this->_get_subdomainID());
        if ($user->role == 3) {
            if ($this->isNotDeleteOrder) {
                $this->flashSession->error("Tên miền này không được phép xóa");
                $url = ACP_NAME . '/orders?active=customer_message';
                return $this->response->redirect($url);
            }
        }

        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = CustomerMessage::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);
            // echo '<pre>'; print_r($listid); echo '</pre>';die;

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

    public function saveNoteAction()
    {
        if ($this->request->getPost() != '') {
            $notes = $this->request->getPost('note');
            if (!empty($notes)) {
                foreach ($notes as $note) {
                    $subdomain = CustomerMessage::findFirstById($note['id']);
                    if ($subdomain) {
                        $subdomain->note = $note['value'];
                        $subdomain->save();
                    }
                }
                echo 1;
            } else {
                echo 0;
            }
        }
        $this->view->disable();
    }
}
