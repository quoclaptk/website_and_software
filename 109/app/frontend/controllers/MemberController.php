<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\Member;
use Modules\Models\Orders;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Modules\Forms\Frontend\SignUpForm;
use Modules\Forms\Frontend\LoginForm;
use Modules\Forms\Frontend\AccountForm;
use Modules\Forms\Frontend\ChangePasswordForm;

class MemberController extends BaseController
{
    protected $_url_login;
    protected $_url_account;

    public function onConstruct()
    {
        parent::onConstruct();
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
        $this->_url_login = $this->view->_url_login = $this->languageCode == 'vi' ? 'dang-nhap' : $this->languageCode . '/login';
        $this->_url_account = $this->view->_url_account = $this->languageCode == 'vi' ? 'tai-khoan' : $this->languageCode . '/account';
        $this->_url_change_pass = $this->view->_url_change_pass = $this->languageCode == 'vi' ? 'tai-khoan/doi-mat-khau' : $this->languageCode . '/account/changePassword';
        $this->_url_order_history = $this->view->_url_order_history = $this->languageCode == 'vi' ? 'tai-khoan/lich-su-don-hang' : $this->languageCode . '/account/historyOrder';
    }

    public function accountAction()
    {
        if (!$this->authFront->getIdentity()) {
            return $this->response->redirect($this->tag->site_url($this->_url_login));
        }

        $titleBar = $this->_word->_('_thong_tin_tai_khoan');
        $breadcrumb = "<li class='active'>$titleBar</li>";
        $member = $this->authFront->getUser();
        $form = new AccountForm($member, ['edit' => true]);
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost()) != false) {
                $member->assign([
                    'fullName' => $this->request->getPost('fullName', 'striptags'),
                    'phone' => $this->request->getPost('phone'),
                    'address' => $this->request->getPost('address', 'striptags')
                ]);

                if ($member->save()) {
                    $this->updateSessionAuth($member);
                    $this->flashSession->success($this->_word->_('_cap_nhat_thong_tin_tai_khoan_thanh_cong'));
                    return $this->response->redirect($this->tag->site_url($this->_url_account));
                }
            }
        }

        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url('tai-khoan') : $this->tag->site_url($langCode . '/account');
            }
        }

        $this->view->languageUrls = $languageUrls;
        $this->view->form = $form;
        $this->view->title_bar = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function signupAction()
    {
        $titleBar = $this->_word->_('_dang_ky_thanh_vien');
        $breadcrumb = "<li class='active'>$titleBar</li>";
        $form = new SignUpForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost()) != false) {
                $member = new Member([
                    'subdomain_id' => $this->_subdomain_id,
                    'username' => $this->request->getPost('username', 'striptags'),
                    'email' => $this->request->getPost('email'),
                    'password' => $this->security->hash($this->request->getPost('password'))
                ]);

                if ($member->save()) {
                    $check = $this->authFront->check([
                        'subdomain_id' => $this->_subdomain_id,
                        'username' => $member->username,
                        'password' => $this->request->getPost('password')
                    ]);

                    if ($check == true) {
                        $this->flashSession->success($this->_word->_('_ban_da_dang_ky_tai_khoan_thanh_cong'));
                        return $this->response->redirect($this->tag->site_url($this->_url_account));
                    }
                }

                $this->flash->error($member->getMessages());
            }
        }

        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url('dang-ky') : $this->tag->site_url($langCode . '/signup');
            }
        }

        $this->view->languageUrls = $languageUrls;
        $this->view->form = $form;
        $this->view->title_bar = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function loginAction()
    {
        if ($this->authFront->getIdentity()) {
            return $this->response->redirect($this->tag->site_url($this->_url_account));
        }

        $titleBar = $this->_word->_('_dang_nhap');
        $breadcrumb = "<li class='active'>$titleBar</li>";
        $form = new LoginForm();

        try {
            if (!$this->request->isPost()) {
                if ($this->authFront->hasRememberMe()) {
                    return $this->auth->loginWithRememberMe();
                }
            } else {
                if ($form->isValid($this->request->getPost()) != false) {
                    $check = $this->authFront->check([
                        'subdomain_id' => $this->_subdomain_id,
                        'username' => $this->request->getPost('username'),
                        'password' => $this->request->getPost('password'),
                        'remember' => $this->request->getPost('remember')
                    ]);

                    if ($check == true) {
                        $this->flashSession->success($this->_word->_('_ban_da_dang_nhap_thanh_cong'));
                        return $this->response->redirect($this->tag->site_url($this->_url_account));
                    }
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url('dang-nhap') : $this->tag->site_url($langCode . '/login');
            }
        }

        $this->view->languageUrls = $languageUrls;
        $this->view->form = $form;
        $this->view->title_bar = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function changePasswordAction()
    {
        if (!$this->authFront->getIdentity()) {
            return $this->response->redirect($this->tag->site_url($this->_url_login));
        }

        $titleBar = $this->_word->_('_doi_mat_khau');
        $breadcrumb = "<li class='active'>$titleBar</li>";
        $form = new ChangePasswordForm();
        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost()) != false) {
                $member = $this->authFront->getUser();
                $member->password = $this->security->hash($this->request->getPost('password'));
                if ($member->save()) {
                    $this->flashSession->success($this->_word->_('_ban_da_doi_mat_khau_thanh_cong'));
                    return $this->response->redirect($this->tag->site_url($this->_url_change_pass));
                }
            }
        }
        
        $languageUrls = [];
        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url('tai-khoan/doi-mat-khau') : $this->tag->site_url($langCode . '/account/changePassword');
            }
        }

        $this->view->languageUrls = $languageUrls;
        $this->view->form = $form;
        $this->view->title_bar = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function forgotPasswordAction()
    {
        $form = new ForgotPasswordForm();

        if ($this->request->isPost()) {

            // Send emails only is config value is set to true
            if ($this->getDI()->get('config')->useMail) {
                if ($form->isValid($this->request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $member = Member::findFirstByEmail($this->request->getPost('email'));
                    if (!$member) {
                        $this->flash->success('There is no account associated to this email');
                    } else {
                        $resetPassword = new ResetPasswords();
                        $resetPassword->usersId = $member->id;
                        if ($resetPassword->save()) {
                            $this->flash->success('Success! Please check your messages for an email reset password');
                        } else {
                            foreach ($resetPassword->getMessages() as $message) {
                                $this->flash->error($message);
                            }
                        }
                    }
                }
            } else {
                $this->flash->warning('Emails are currently disabled. Change config key "useMail" to true to enable emails.');
            }
        }

        $this->view->form = $form;
    }

    public function historyOrderAction()
    {
        if (!$this->authFront->getIdentity()) {
            return $this->response->redirect($this->tag->site_url($this->_url_login));
        }

        $titleBar = $this->_word->_('_lich_su_don_hang');
        $breadcrumb = "<li class='active'>$titleBar</li>";
        $member = $this->authFront->getUser();

        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url('tai-khoan/lich-su-don-hang') : $this->tag->site_url($langCode . '/account/historyOrder');
            }
        }

        $orders = Orders::find([
            "conditions" => "member_id = $member->id",
            "order" => "id DESC",
        ]);

        $this->view->languageUrls = $languageUrls;
        $this->view->member = $member;
        $this->view->orders = $orders;
        $this->view->title_bar = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function historyOrderDetailAction($id)
    {
        if (!$this->authFront->getIdentity()) {
            return $this->response->redirect($this->tag->site_url($this->_url_login));
        }

        $order = Orders::findFirstById($id);
        if (!$order) {
            $this->flashSession->error($this->_word->_('_don_hang_khong_ton_tai'));
            return $this->response->redirect($this->tag->site_url($this->_url_account));
        }

        $titleBar = "". $this->_word->_('_don_hang_ma_so') ." #" . $order->code;
        $breadcrumb = "<li class='active'>$titleBar</li>";
        $member = $this->authFront->getUser();

        if (count($this->_tmpSubdomainLanguages) > 0) {
            foreach ($this->_tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url('tai-khoan/lich-su-don-hang/' . $id) : $this->tag->site_url($langCode . '/account/historyOrder/' . $id);
            }
        }

        $this->view->languageUrls = $languageUrls;
        $this->view->member = $member;
        $this->view->order = $order;
        $this->view->title_bar = $titleBar;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function logoutAction()
    {
        $this->authFront->remove();

        return $this->response->redirect($this->tag->site_url($this->_url_login));
    }

    protected function checkIdentity()
    {
        if (!$this->authFront->getIdentity()) {
            return $this->response->redirect($this->tag->site_url($this->_url_login));
        }
    }

    protected function updateSessionAuth(Member $member)
    {
        $guesInfo = $member->toArray();
        $guesInfo['isLogin'] = true;
        unset($guesInfo['password']);
        $this->session->set('auth-guest', $guesInfo);
    }
}
