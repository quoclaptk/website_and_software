<?php namespace Modules\Backend\Controllers;

use Modules\Forms\UserSubdomainForm;
use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use
    Modules\Models\Users;
use Modules\Models\Subdomain;
use Modules\Models\UserHistory;
use Modules\Models\UserHistoryTransfer;
use Modules\Mail\Mail;// Mail\Mail lan luot la thu muc va file
use Modules\PhpExcels;//PhpExcels chinh la file
use Modules\Models\PasswordChanges;
use Modules\Forms\UsersForm;
use Modules\Forms\ChangePasswordForm;
use Modules\Forms\NganluongPaymentForm;

use Modules\Models\TmpSubdomainUser;
use Modules\OnlinePayment\NganLuong;
use Phalcon\Mvc\View;

/**
 * Modules\Controllers\UsersController
 *
 * CRUD to manage users
 */
class UsersController extends BaseController
{
    public function onConstruct()
    {
        $this->view->module_name = 'Quản lý tài khoản';
    }


    /**
     * Default action, shows the search form
     */
    public function indexAction()
    {
        $users = Users::find(
            array(
                        "conditions" => "profilesId != 1",
                        "order" => "sort ASC, id DESC"
                    )
        );

        $numberPage = $this->request->getQuery("page", "int");

        $paginator = new Paginator(
            array(
                    "data" => $users,
                    "limit" => 10,
                    "page" => $numberPage
                )

        );
        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';

        $this->view->page = $paginator->getPaginate();
        $this->view->breadcrumb = $breadcrumb;
    }

    /**
     * Searches for users
     */
    public function searchAction()
    {
        $numberPage = 1;
        if ($this->request->isPost()) {
            $query = Criteria::fromInput($this->di, 'Modules\Models\Users', $this->request->getPost());
            //print_r($query);
            $this->persistent->searchParams = $query->getParams();
        } else {
            $numberPage = $this->request->getQuery("page", "int");
        }

        $parameters = array();
        if ($this->persistent->searchParams) {
            $parameters = $this->persistent->searchParams;
        }

        $users = Users::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice("The search did not find any users");
            return $this->dispatcher->forward(array(
                            "action" => "index"
                    ));
        }

        $paginator = new Paginator(array(
                    "data" => $users,
                    "limit" => 1,
                    "page" => $numberPage
        ));

        $this->view->page = $paginator->getPaginate();

        //echo '<pre>'; print_r($paginator); echo '</pre>';
    }

    /**
     * Creates a User
     *
     */
    public function createAction()
    {
        $form = new UsersForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            $user = new Users();

            $user->assign(array(
                            'username' => $this->request->getPost('username', 'striptags'),
                            'profilesId' => $this->request->getPost('profilesId', 'int'),
                            'password' => $this->security->hash($this->request->getPost('password')),
                            'email' => $this->request->getPost('email', 'email'),
                            'sort' => $this->request->getPost('sort'),
                            'banned' => $this->request->getPost('banned'),
                            'suspended' => $this->request->getPost('suspended'),
                            'active' => $this->request->getPost('active')
            ));

            if (!$user->save()) {
                $this->flash->error($user->getMessages());
            } else {
                $this->flash->success("User was created successfully");
                return $this->response->redirect('acp/users');
            }
        }
        $this->view->form = $form;
    }

    /**
     * Saves the user from the 'edit' action
     *
     */
    public function editAction($id)
    {
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error("User was not found");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        $form = new UsersForm($user, array('edit' => true));

        if ($this->request->isPost()) {
            $user->assign(array(
                        'username' => $this->request->getPost('username', 'striptags'),
                        'profilesId' => $this->request->getPost('profilesId', 'int'),
                        'email' => $this->request->getPost('email', 'email'),
                        'sort' => $this->request->getPost('sort'),
                        'banned' => $this->request->getPost('banned'),
                        'suspended' => $this->request->getPost('suspended'),
                        'active' => $this->request->getPost('active')
                    ));

            if (!$user->save()) {
                $this->flash->error($user->getMessages());
            } else {
                $this->flash->success("User was updated successfully");
                //return $this->response->redirect('acp/users');
                Tag::resetInput();
            }
        }

        $this->view->user = $user;

        $this->view->form = $form;
    }

    /**
     * Deletes a User
     *
     * @param int $id
     */
    public function deleteAction($id)
    {
        $user = Users::findFirstById($id);
        if (!$user) {
            $this->flash->error("User was not found");
            return $this->dispatcher->forward(array('action' => 'index'));
        }

        if (!$user->delete()) {
            $this->flash->error($user->getMessages());
        } else {
            $this->flash->success("User was deleted");
        }

        return $this->dispatcher->forward(array('action' => 'index'));
    }

    /**
     * Users must use this action to change its password
     *
     */
    public function changePasswordAction()
    {
        $form = new ChangePasswordForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            if (!$form->isValid($this->request->getPost())) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $user = $this->auth->getUser();

                $user->password = $this->security->hash($this->request->getPost('password'));
                $user->mustChangePassword = 'N';
                if ($user->save()) {
                    $this->flash->success('Đổi mật khẩu thành công');
                /*$passwordChange = new PasswordChanges();
                $passwordChange->usersId = $user->id;
                $passwordChange->ipAddress = $this->request->getClientAddress();
                $passwordChange->userAgent = $this->request->getUserAgent();

                if (!$passwordChange->save()) {
                        $this->flash->error($passwordChange->getMessages());
                } else {
                    $this->flash->success('Đổi mật khẩu thành công');
                    // Tag::resetInput();
                }*/
                } else {
                    $this->flash->error($passwordChange->getMessages());
                }
            }
        }

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';

        $this->view->breadcrumb = $breadcrumb;
        $this->view->form = $form;
    }

    public function changePasswordUserAction($id)
    {
        $identity = $this->auth->getIdentity();

        if ($identity['role'] == 1) {
            $conditions = "id = $id";
        } else {
            $conditions = "id = $id AND create_id = ". $identity['id'] ."";
        }

        $subdomainUser = Subdomain::findFirst([
            "conditions" => $conditions
        ]);
        
        if (!$subdomainUser) {
            $tmpSubdomainUser = TmpSubdomainUser::findBySubdomainId($identity['subdomain_id']);
            if (count($tmpSubdomainUser) > 0) {
                $arrayUserId = [];
                foreach ($tmpSubdomainUser as $tmp) {
                    $arrayUserId[] = $tmp->user_id;
                }
                $arrayUserId = (count($arrayUserId) > 1) ? implode(",", $arrayUserId) : $arrayUserId[0];
                $user = Users::findFirst(
                    array(
                        "conditions" => "active = 'Y' AND id IN ($arrayUserId) AND subdomain_id = $id",
                    )
                );
                if ($user) {
                    $subdomainUser = Subdomain::findFirstByid($user->subdomain_id);
                }
            }
        } else {
            $user = Users::findFirstBySubdomainId($id);
        }

        if (!$user) {
            return false;
        }
        
        $this->view->setRenderLevel(
            View::LEVEL_ACTION_VIEW
        );

        if ($this->request->isPost()) {
            if ($this->request->getPost('password') != '') {
                $user->password = $this->security->hash($this->request->getPost('password'));
                $user->mustChangePassword = 'N';

                /*$passwordChange = new PasswordChanges();
                $passwordChange->user = $user;
                $passwordChange->ipAddress = $this->request->getClientAddress();
                $passwordChange->userAgent = $this->request->getUserAgent();*/

                if (!$user->save()) {
                    die(json_encode(1));
                    $this->flash->error($passwordChange->getMessages());
                } else {
                    die(json_encode(0));
                    $this->flash->success('Đổi mật khẩu thành công');
                }
            }
        }

        $breadcrumb = '<li class="active">'.$this->view->module_name.'</li>';

        $this->view->breadcrumb = $breadcrumb;
        $this->view->title_bar = 'Đổi mật khẩu cho tên miền ' . $subdomainUser->name . '.' . $this->mainGlobal->getRootDomain();
        $this->view->id = $id;
        $this->view->user = $user;
        $this->view->pick($this->_getControllerName() . '/change_password_user');
    }

    public function changePasswordUserPostAction()
    {
        if ($this->request->isPost()) {
            $id = $this->request->getPost('id');
            $identity = $this->auth->getIdentity();
            if ($identity['role'] == 1) {
                $conditions = "id = $id";
            } else {
                $conditions = "id = $id AND create_id = ". $identity['id'] ."";
            }
            $subdomainUser = Subdomain::findFirst([
                "conditions" => $conditions
            ]);
            

            if (!$subdomainUser) {
                $tmpSubdomainUser = TmpSubdomainUser::findBySubdomainId($identity['subdomain_id']);
                if (count($tmpSubdomainUser) > 0) {
                    $arrayUserId = [];
                    foreach ($tmpSubdomainUser as $tmp) {
                        $arrayUserId[] = $tmp->user_id;
                    }
                    $arrayUserId = (count($arrayUserId) > 1) ? implode(",", $arrayUserId) : $arrayUserId[0];
                    $user = Users::findFirst(
                        array(
                            "conditions" => "active = 'Y' AND id IN ($arrayUserId) AND subdomain_id = $id",
                        )
                    );
                    if ($user) {
                        $subdomainUser = Subdomain::findFirstByid($user->subdomain_id);
                    }
                }
            } else {
                $user = Users::findFirstBySubdomainId($id);
            }

            if (!$user) {
                return false;
            }

            if ($this->request->getPost('password') != '') {
                $user->password = $this->security->hash($this->request->getPost('password'));
                $user->mustChangePassword = 'N';

                /*$passwordChange = new PasswordChanges();
                $passwordChange->user = $user;
                $passwordChange->ipAddress = $this->request->getClientAddress();
                $passwordChange->userAgent = $this->request->getUserAgent();*/

                if (!$user->save()) {
                    die(json_encode(0));
                    $this->flash->error($passwordChange->getMessages());
                } else {
                    die(json_encode(1));
                    $this->flash->success('Đổi mật khẩu thành công');
                    Tag::resetInput();
                }
            }
        }

        $this->view->disable();
    }

    public function deletemultyAction()
    {
        $listid = $this->request->getQuery('listid');

        $listid = explode(',', $listid);

        $d = 0;
        foreach ($listid as $id) {
            $user = Users::findFirstById($id);
            if ($user) {
                $user->delete();
                $d++;
            }
        }
        //echo $d;die;
        if ($d > 0) {
            $this->flash->success("User was deleted");
        } else {
            $this->flash->error("User was not found");
        }
        return $this->dispatcher->forward(array('action' => 'index'));
    }

    public function createsubdomainAction()
    {
        $this->view->setRenderLevel(
            View::LEVEL_ACTION_VIEW
        );
        $form = new UserSubdomainForm();
        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
        }
        $this->view->title_bar = 'Tạo web';
        $this->view->form = $form;
        $this->view->pick($this->_getControllerName() . '/form');
    }

    public function changeBalanceAction()
    {
        if ($this->request->isPost()) {
            if ($this->request->isAjax()) {
                $identity = $this->auth->getIdentity();
                $id = $this->request->getPost('id');
                $balance = $this->request->getPost('balance');
                $user = Users::findFirstBySubdomainId($id);
                if ($user) {
                    $user->assign(['balance' => $balance]);
                    if ($user->save()) {
                        echo 1;
                    } else {
                        echo 0;
                    }
                } else {
                    echo -1;
                }
            }
        }
        $this->view->disable();
    }

    public function viewUserHistoryAction()
    {
        if ($this->request->isPost()) {
            if ($this->request->isAjax()) {
                $this->view->setRenderLevel(
                    View::LEVEL_ACTION_VIEW
                );
                $id = $this->request->getPost('id');
                $user = Users::findFirstBySubdomainId($id);
                $userHistories = UserHistory::find([
                    'conditions' => 'user_id = '. $user->id .'',
                    'order' => 'id DESC'
                ]);
                $userHistoryTransfers = UserHistoryTransfer::find([
                    'conditions' => 'user_id = '. $user->id .'',
                    'order' => 'id DESC'
                ]);
                $this->view->user = $user;
                $this->view->userHistories = $userHistories;
                $this->view->userHistoryTransfers = $userHistoryTransfers;
                $this->view->pick($this->_getControllerName() . '/view_user_history');
            }
        }
    }

    public function viewUserHistoryTransferAction()
    {
        if ($this->request->isPost()) {
            if ($this->request->isAjax()) {
                $this->view->setRenderLevel(
                    View::LEVEL_ACTION_VIEW
                );
                $id = $this->request->getPost('id');
                $user = Users::findFirstBySubdomainId($id);
                $userHistoryTransfers = UserHistoryTransfer::find([
                    'conditions' => 'user_id = '. $user->id .'',
                    'order' => 'id DESC'
                ]);
                $this->view->user = $user;
                $this->view->userHistoryTransfers = $userHistoryTransfers;
            }
        }
    }

    public function historyTransferAction($id)
    {
        $identity = $this->auth->getIdentity();
        $user = Users::findFirstById($id);
        if (!$user || $identity['id'] != $id) {
            $this->flashSession->error('Không tìm thấy dữ liệu!');
            return $this->dispatcher->forward(
                [
                    "modules" => "backend",
                    "controller" => "index",
                    "action"     => "index",
                ]
            );
        }
        $breadcrumb = '<li class="active">Lịch sử giao dịch</li>';
        $identity = $this->auth->getIdentity();
        $historyTransfers = UserHistoryTransfer::find([
            'conditions' => 'user_id = '. $id .'',
            'order' => 'id DESC'
        ]);
        $this->view->historyTransfers = $historyTransfers;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function nganluongPaymentAction()
    {
        $breadcrumb = '<li class="active">Nạp tiền</li>';
        $identity = $this->auth->getIdentity();
        $form = new NganluongPaymentForm();

        if ($this->request->isPost() && $form->isValid($this->request->getPost())==true) {
            //Mã đơn hàng
            $order_code='OP_NL_' . $identity['id'] . '_' . time();
            //Khai báo url trả về
            $return_url= 'http:' . HTTP_HOST . '/' . ACP_NAME . '/payment/nganLuongProcess';
            // Link nut hủy đơn hàng
            $cancel_url= 'http:' . HTTP_HOST . '/' . ACP_NAME . '/users/nganluongPayment';

            //Giá của cả giỏ hàng
            $price = $this->request->getPost('amount', 'int');
            //Thông tin giao dịch
            $transaction_info = "Thong tin giao dich";
            $currency= "vnd";
            $quantity = 1;
            $tax = 0;
            $discount = 0;
            $fee_cal = 0;
            $fee_shipping = 0;
            $order_description = "Thong tin giao dich: " . $order_code;
            $buyer_info = $identity['id'] . "*|*" . $identity['username'];
            $affiliate_code="";

            $nganLuong = new NganLuong();
            $url = $nganLuong->orderProcessUrl($return_url, $cancel_url, $transaction_info, $order_code, $price, $currency, $quantity, $tax, $discount, $fee_cal, $fee_shipping, $order_description, $buyer_info, $affiliate_code);

            return $this->response->redirect($url);
        }

        $this->view->form = $form;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function updateSubdomainIdAction()
    {
        $userHistoryTransfers = UserHistoryTransfer::find();
        foreach ($userHistoryTransfers as $userHistoryTransfer) {
            if ($userHistoryTransfer->user) {
                $userHistoryTransfer->subdomain_id = $userHistoryTransfer->user->subdomain_id;
                $userHistoryTransfer->save();
            }
        }
    }
}
