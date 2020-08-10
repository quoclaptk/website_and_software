<?php

namespace Modules\Forms\Frontend;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\Callback;
use Modules\Models\Member;
use Modules\PhalconVn\MainGlobal;

class LoginForm extends Form
{
    public function initialize()
    {
        $mainGlobal = new MainGlobal();
        $this->_subdomain_id = $mainGlobal->getDomainId();

        $username = new Text('username', array(
            'placeholder' => 'Username hoặc Email'
        ));

        $username->addValidators(array(
            new PresenceOf(array(
                'message' => 'Bạn chưa nhập Username hoặc Email'
            )),
            new Callback(
                [
                    "message" => "Tên đăng nhập không đúng hoặc chưa đăng ký",
                    "callback" => function () {
                        if (!($this->checkUsername($this->request->getPost('username')))) {
                            return false;
                        }
                     
                        return true;
                    }
                ]
            )
        ));
        $this->add($username);

        //Password
        $password = new Password('password', array(
            'placeholder' => 'Mật khẩu',
        ));

        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Bạn chưa nhập mật khẩu'
            )),
            new Callback(
                [
                    "message" => "Mật khẩu bạn nhập không đúng",
                    "callback" => function () {
                        if (!($this->checkPassword($this->request->getPost('username'), $this->request->getPost('password')))) {
                            return false;
                        }
                     
                        return true;
                    }
                ]
            )
        ));

        $this->add($password);

        //Remember
        $remember = new Check('remember', array(
            'value' => 'yes'
        ));

        $remember->setLabel('Remember me');

        $this->add($remember);

        //CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(
            new Identical(array(
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF validation failed'
            ))
        );

        $this->add($csrf);

        $this->add(new Submit('go', array(
            'class' => 'btn btn-primary btn-large'
        )));
    }

    private function checkUsername($username)
    {
        $member = Member::findFirst([
            'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND (username = "'. $username .'" OR email = "'. $username .'")'
        ]);

        if (!$member) {
            return false;
        }

        return  true;
    }

    private function checkPassword($username, $password)
    {
        if (self::checkUsername($username)) {
            $member = Member::findFirst([
                'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND (username = "'. $username .'" OR email = "'. $username .'")'
            ]);

            if (!$this->security->checkHash($password, $member->password)) {
                return false;
            }

            return true;
        }
        
        return true;
    }

    public function messages($username)
    {
        if ($this->hasMessagesFor($username)) {
            foreach ($this->getMessagesFor($username) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
