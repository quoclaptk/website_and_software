<?php namespace Modules\Frontend\Controllers;

use Modules\Forms\LoginForm;
use Modules\Forms\SignUpForm;
use Modules\Forms\ForgotPasswordForm;
use Modules\Auth\AuthFront;
use Modules\Auth\Exception as AuthException;
use Modules\Frontend\Models\Users;
use Modules\Frontend\Models\ResetPasswords;

class SessionController extends BaseController
{
    public function initialize()
    {
        $this->view->setTemplateBefore('public');
    }

    public function indexAction()
    {
        $this->view->form = new LoginForm();
    }

    public function signupAction()
    {
        $form = new SignUpForm(null);

        $request = $this->request;
        if ($request->isPost()) {
            //var_dump($form->isValid($request->getPost()));
            if ($form->isValid($request->getPost()) != false) {
                $User = new Users();
                $user=array(
                    'username' => $request->getPost('username', 'striptags'),
                    'email' => $request->getPost('email', 'striptags'),
                    'password' => $this->security->hash($request->getPost('password')),
                    'profilesId' => 2,
                    'fullName'	=> $request->getPost('fullName', 'striptags'),
                    'phone'		=> $request->getPost('phone', 'int'),
                    'sex'		=> $request->getPost('sex'),
                    'birthday'	=> $request->getPost('birthday'),
                    'cardId'	=> $request->getPost('cardId'),
                    'cityRegion'=>  $request->getPost('cityRegion'),

                );
                if ($User->save($user)) {
                    return $this->dispatcher->forward(array(
                        'controller' => 'index',
                        'action' => 'index'
                    ));
                }

                $this->flash->error($User->getMessages());
            }
        }

        $this->view->form = $form;
    }

    /**
     * Starts a session in the admin backend
     */
    public function loginAction()
    {
        $form = new LoginForm();
        $request = $this->request;
        $identity = $this->authFront->getIdentity();

        try {
            if (!$request->isPost()) {
                if ($this->authFront->hasRememberMe()) {
                    return $this->authFront>loginWithRememberMe();
                }
                //check if login without check remember
                if (is_array($identity)) {
                    return $this->response->redirect('users/');
                }
            } else {
                if ($form->isValid($request->getPost()) == false) {
                    foreach ($form->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                } else {
                    $this->authFront->check(array(
                        'email' => $request->getPost('email'),
                        'password' => $request->getPost('password'),
                        'remember' => $request->getPost('remember')
                    ));
                    //print_r($identity);
                    return $this->response->redirect('users');
                }
            }
        } catch (AuthException $e) {
            $this->flash->error($e->getMessage());
        }

        $this->view->form = $form;
    }

    /**
     * Shows the forgot password form
     */
    public function forgotPasswordAction()
    {
        $form = new ForgotPasswordForm();

        if ($request->isPost()) {
            if ($form->isValid($request->getPost()) == false) {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $user = Users::findFirstByEmail($request->getPost('email'));
                if (!$user) {
                    $this->flash->success('There is no account associated to this email');
                } else {
                    $resetPassword = new ResetPasswords();
                    $resetPassword->usersId = $user->id;
                    if ($resetPassword->save()) {
                        $this->flash->success('Success! Please check your messages for an email reset password');
                    } else {
                        foreach ($resetPassword->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    }
                }
            }
        }

        $this->view->form = $form;
    }

    /**
     * Closes the session
     */
    public function logoutAction()
    {
        $this->authFront->remove();
        $this->flash->success('Goodbye!');
        return $this->response->redirect('index');
    }
}
