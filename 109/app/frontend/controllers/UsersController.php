<?php

namespace Modules\Frontend\Controllers;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;

use Modules\Models\Users;
use Modules\Models\Subdomain;
use Phalcon\Http\Response;
use Phalcon\Security\Random;
use Modules\Auth\UnsafeCrypto;

class UsersController extends BaseController
{
    protected $_key;

    public function onConstruct()
    {
        parent::onConstruct();
        $this->_key = hex2bin('000102030405060708090a0b0c0d0e0f101112131415161718191a1b1c1d1e1f');
    }

    public function systemLoginAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $username = $this->request->getPost('l_username');
            $password = $this->request->getPost('l_password');
            $user = Users::findFirstByUsername($username);
            if ($user) {
                $subdomain = $user->subdomain;
                if ($subdomain) {
                    $random = new Random();
                    $user = Users::findFirstById($user->id);
                    $user->token = $random->base58(24);
                    if ($user->save()) {
                        $code = UnsafeCrypto::encrypt($password, $this->_key, true);
                        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                        $url = ($subdomain->name != '@') ? $protocol . $subdomain->name . '.' . ROOT_DOMAIN . '/token-login?code=' . $code . '&token=' . $user->token : $protocol . ROOT_DOMAIN . '/token-login?code=' . $code . '&token=' . $user->token;
                        $result = ['status' => 200, 'url' => $url];
                    } else {
                        $result = ['status' => 500, 'message' => 'Error token'];
                    }
                } else {
                    $result = ['status' => 500, 'message' => 'Not found subdomain'];
                }
            } else {
                $result = ['status' => 500, 'message' => 'Not found user'];
            }

            $response = new Response();
            $response->setContent(json_encode($result));

            return $response;
        }

        $this->view->disable();
    }

    public function tokenLoginAction()
    {
        if ($this->request->hasQuery('code') && $this->request->hasQuery('token')) {
            $subdomain = $this->mainGlobal->checkDomain();
            $code = $this->request->get('code');
            $token = $this->request->get('token');
            $user = Users::findFirst([
                "conditions" => "subdomain_id = $subdomain->id AND token = '$token'"
            ]);

            if ($user) {
                $password = UnsafeCrypto::decrypt($code, $this->_key, true);
                $this->auth->check(array(
                    'subdomain_id' => $subdomain->id,
                    'username' => $user->username,
                    'password' => $password
                ));

                if (!empty($this->session->get('auth-identity'))) {
                    $user = Users::findFirstById($user->id);
                    $user->token = null;
                    $user->save();

                    $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

                    $url = ($user->subdomain->name != '@') ? $protocol . $user->subdomain->name . '.' . ROOT_DOMAIN . '/hi' : $protocol . ROOT_DOMAIN . '/hi';

                    $this->response->redirect($url);
                } else {
                    $this->response->redirect('//' . ROOT_DOMAIN . '?msg=error_password');
                }
            }
        }
    }

    public function checkUsernameExistAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $username = $this->request->getPost('username');
            $users = Users::findByUsername($username);
            $response = new Response();
            $response->setContent(json_encode($users->count()));

            return $response;
        }

        $this->view->disable();
    }

    public function checkEmailExistAction()
    {
        if ($this->request->isPost() && $this->request->isAjax()) {
            $email = $this->request->getPost('email');
            $users = Users::findByEmail($email);
            $response = new Response();
            $response->setContent(json_encode($users->count()));

            return $response;
        }

        $this->view->disable();
    }
}
