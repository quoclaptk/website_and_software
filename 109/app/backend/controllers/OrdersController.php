<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Orders;
use Modules\Models\Contact;
use Modules\Models\Newsletter;
use Modules\Models\CustomerMessage;
use Modules\Models\CustomerComment;
use Modules\Models\FormItem;
use Modules\Models\Subdomain;
use Modules\Models\Users;
use Modules\Forms\OrdersForm;
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
use Phalcon\Paginator\Adapter\QueryBuilder;
use Phalcon\Http\Response;
use Phalcon\Mvc\View;

class OrdersController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Đơn hàng';
    }

    public function indexAction()
    {
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
                    ->from(MODELS . '\Orders')
                    ->where("subdomain_id IN ($listId) AND deleted = 'N'")
                    ->orderBy("id DESC");


                $contact = Contact::find(
                    array(
                        "order" => "id DESC",
                        "conditions" => "subdomain_id IN ($listId) AND deleted = 'N'"
                    )
                );

                $newsletter = Newsletter::find(
                    array(
                        "order" => "id DESC",
                        "conditions" => "subdomain_id IN ($listId) AND deleted = 'N'"
                    )
                );

                $customerMessages = $this->modelsManager->createBuilder()
                    ->from(MODELS . '\CustomerMessage')
                    ->where("subdomain_id IN ($listId) AND deleted = 'N'")
                    ->orderBy("id DESC");

                $frmItemYcbg = $this->modelsManager->createBuilder()
                    ->from(MODELS . '\FormItem')
                    ->where("subdomain_id IN ($listId) AND form_group_id = 1 AND deleted = 'N'")
                    ->orderBy("id DESC");

                $frmItemFastRegister = FormItem::find(
                    array(
                        "order" => "id DESC",
                        "conditions" => "subdomain_id IN ($listId) AND form_group_id = 2 AND deleted = 'N'"
                    )
                );

                $customerComments = CustomerComment::find(
                    array(
                        "order" => "id DESC",
                        "conditions" => "subdomain_id IN ($listId) AND deleted = 'N'"
                    )
                );
            }
        } else {
            $list = $this->modelsManager->createBuilder()
                    ->from(MODELS . '\Orders')
                    ->where("subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'")
                    ->orderBy("id DESC");

            $contact = Contact::find(
                array(
                    "order" => "id DESC",
                    "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
                )
            );

            $newsletter = Newsletter::find(
                array(
                    "order" => "id DESC",
                    "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
                )
            );

            $customerMessages = $this->modelsManager->createBuilder()
                    ->from(MODELS . '\CustomerMessage')
                    ->where("subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'")
                    ->orderBy("id DESC");

            $frmItemYcbg = $this->modelsManager->createBuilder()
                    ->from(MODELS . '\FormItem')
                    ->where("subdomain_id = ". $this->_get_subdomainID() ." AND form_group_id = 1 AND deleted = 'N'")
                    ->orderBy("id DESC");
            $frmItemFastRegister = FormItem::find(
                array(
                    "order" => "id DESC",
                    "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND form_group_id = 2 AND deleted = 'N'"
                )
            );

            $customerComments = CustomerComment::find(
                array(
                    "order" => "id DESC",
                    "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
                )
            );
        }

        $paginatorOrders = new QueryBuilder(
            [
                "builder" => $list,
                "limit"   => 50,
                "page"    => 1,
            ]
        );

        $paginatorCustomerMessages = new QueryBuilder(
            [
                "builder" => $customerMessages,
                "limit"   => 50,
                "page"    => 1,
            ]
        );

        $paginatorFrmItemYcbg = new QueryBuilder(
            [
                "builder" => $frmItemYcbg,
                "limit"   => 50,
                "page"    => 1,
            ]
        );

        $numberPage = $this->request->getQuery("page", "int");

        $page_current = ($numberPage > 1) ? $numberPage : 1;
        

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';
        $this->view->breadcrumb = $breadcrumb;
        // $this->view->page = $paginator->getPaginate();
        $this->view->page_current = $page_current;
        $this->view->pageOrders = $paginatorOrders->getPaginate();
        $this->view->pageCustomerMessages = $paginatorCustomerMessages->getPaginate();
        $this->view->pageFrmItemYcbg = $paginatorFrmItemYcbg->getPaginate();
        $this->view->user = $user;
        $this->view->contact = $contact;
        $this->view->newsletter = $newsletter;
        $this->view->frm_item_fast_register = $frmItemFastRegister;
        $this->view->customerComments = $customerComments;
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
                        ->from(MODELS . '\Orders')
                        ->conditions("subdomain_id IN ($listId) AND deleted = 'N'")
                        ->orderBy("id DESC");
                }
            } else {
                $list = $this->modelsManager->createBuilder()
                    ->from(MODELS . '\Orders')
                    ->where("subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'")
                    ->orderBy("id DESC");
            }

            $paginatorOrders = new QueryBuilder(
                [
                    "builder" => $list,
                    "limit"   => 50,
                    "page"    => $page,
                ]
            );

            $this->view->user = $user;
            $this->view->page_current = 1;
            $this->view->pageOrders = $paginatorOrders->getPaginate();
            $this->view->url_page = ACP_NAME . '/orders/allItems';
            $this->view->pick($this->_getControllerName() . '/allItems');
        }
    }

    public function updateAction($id, $page = 1)
    {
        $item = Orders::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
        ]);
        if (!$item) {
            $this->flash->error("Không tìm thấy dữ liệu");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $form = new OrdersForm($item, array('edit' => true));

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $save_close = $this->request->getPost('save_close');

            $item->assign([
                "note" => $this->request->getPost("note"),
                "order_status" => $this->request->getPost("order_status")
            ]);

            if ($item->save()) {
                $this->flashSession->success($this->_message["edit"]);
                if (!empty($save_close)) {
                    $url = ($page > 1) ? ACP_NAME . '/' . $this->_getControllerName() . '?page=' . $page : ACP_NAME . '/' . $this->_getControllerName();
                } else {
                    $url = ACP_NAME . '/' . $this->_getControllerName() . '/update/' . $id . '/' . $page;
                }

                $this->response->redirect($url);
            } else {
                $this->flash->error($item->getMessages());
            }
        }

        $this->view->title_bar = 'Chi tiết đơn hàng';
        $breadcrumb = '<li><a href="'. HTTP_HOST . '/' . ACP_NAME . '/' . $this->_getControllerName(). '">'. $this->view->module_name. '</a></li><li class="active">'. $this->view->title_bar .'</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
        $this->view->item = $item;
    }

    public function deleteAction($id, $page = 1)
    {
        $item = Orders::findFirst([
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
        $this->response->redirect($url);
    }

    public function deletemultyAction($page = 1)
    {
        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = Orders::findFirst([
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
        if ($d > 0) {
            $this->flashSession->success($this->_message["delete"]);
        } else {
            $this->flashSession->error("Không tìm thấy dữ liệu");
        }
        $this->response->redirect($url);
    }

    public function _deleteAction($id, $page = 1)
    {
        /// check subdomain order
        $user = Users::findFirstBySubdomainId($this->_get_subdomainID());
        if ($user->role == 3) {
            if ($this->isNotDeleteOrder) {
                $this->flashSession->error("Tên miền này không được phép xóa");
                $url = ACP_NAME . '/orders';
                return $this->response->redirect($url);
            }
        }

        $item = Orders::findFirst([
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
        $this->response->redirect($url);
    }

    public function _deletemultyAction($page = 1)
    {
        /// check subdomain order
        $user = Users::findFirstBySubdomainId($this->_get_subdomainID());
        if ($user->role == 3) {
            if ($this->isNotDeleteOrder) {
                $this->flashSession->error("Tên miền này không được phép xóa");
                $url = ACP_NAME . '/orders';
                return $this->response->redirect($url);
            }
        }

        $listid = $this->request->getQuery('listid');
        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $item = $item = Orders::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND id = $id"
            ]);

            if ($item->delete()) {
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

    /**
    * Action Ajax
    * @return bool
    */
    public function updateValueAction()
    {
        //check action post and is ajax
        if ($this->request->isPost() && $this->request->isAjax()) {
            $result = 1;
            $response = new Response();
            // get all value post on view
            $data = $this->request->getPost();

            // check form update value is order tab
            if ($data['title'] == 'order') {
                // get form_item_id
                $formItem = FormItem::findFirstById($data['id']);
                // check $formItem is false return error message
                if (!$formItem) {
                    $response->setContent(0);
                    return $response;

                }

                $formItem->note = $data['note'];
                // check save data fail
                if (!$formItem->save()) {
                    $response->setContent(0);
                    return $response;
                }
            } 

            // check form update value is custom message tab    
            if ($data['title'] == 'customMess') {
                // get cusMess_id
                $customerMessages = CustomerMessage::findFirstById($data['id']);
                // check $customerMessages is false return error message
                if (!$customerMessages) {
                    $response->setContent(0);
                    return $response;

                }

                $customerMessages->note = $data['note'];
                // check save data fail
                if (!$customerMessages->save()) {
                    $response->setContent(0);
                    return $response;
                }
            }

            // check form update value is request price tab
            if ($data['title'] == 'formItem') {
                // get form_item_id
                $formItem = FormItem::findFirstById($data['id']);
                // check $formItem is false return error message
                if (!$formItem) {
                    $response->setContent(0);
                    return $response;

                }

                $formItem->note = $data['note'];
                // check save data fail
                if (!$formItem->save()) {
                    $response->setContent(0);
                    return $response;
                }
            } 
            
            $response->setContent($result);
            return $response;
        }
    }
}
