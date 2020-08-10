<?php

namespace Modules\Forms\Frontend;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Alpha as AlphaValidator;
use Phalcon\Validation\Validator\Callback;
use Modules\Models\Member;
use Modules\PhalconVn\MainGlobal;

class SignUpForm extends Form
{
    public function initialize($entity=null, $options=null)
    {
        $mainGlobal = new MainGlobal();
        $this->_subdomain_id = $mainGlobal->getDomainId();

        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Hidden('id');
        }
                
        $username = new Text('username', array('placeholder' =>'Username'));
        $username->setLabel('Tên đăng nhập');

        $username->addValidators(array(
            /*new PresenceOf(array(
                'message' => 'Bạn chưa nhập tên đăng nhập'
            )),*/
            new Regex(array(
                "pattern" => "/^[a-z-0-9]+$/",
                'message' => 'Tên đăng nhập viết liền không dấu, từ a-z và 0-9'
            )),
            new Callback(
                [
                    "message" => "Username đã được đăng ký",
                    "callback" => function () {
                        if (!($this->checkDuplicateEmail($this->request->getPost('username')))) {
                            return false;
                        }
                     
                        return true;
                    }
                ]
            )
        ));
        $this->add($username);
                
        $email = new Text('email', array('placeholder'=>'Email'));
        $email->setLabel('E-mail');
        $email->addValidators(array(
            new Email(array(
                'message' => 'Địa chỉ email không hợp lệ'
            )),
            new Callback(
                [
                    "message" => "Email đã được đăng ký",
                    "callback" => function () {
                        if (!($this->checkDuplicateEmail($this->request->getPost('email')))) {
                            return false;
                        }
                     
                        return true;
                    }
                ]
            )
        ));
        $this->add($email);

        //Password
        $password = new Password('password');

        $password->setLabel('Mật Khẩu');

        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Bạn chưa nhập mật khẩu'
            )),
            /*new StringLength(array(
                'min' => 8,
                'messageMinimum' => 'Mật khẩu phải lớn hơn 8 ký tự'
            )),*/
            new Confirmation([
                'message' => 'Mật khẩu xác nhận không khớp',
                'with' => 'confirmPassword'
            ])
        ));

        $this->add($password);

        //Confirm Password
        $confirmPassword = new Password('confirmPassword');

        $confirmPassword->setLabel('Nhập lại mật khẩu');

        $confirmPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'Bạn chưa nhập mật khẩu xác nhận'
            ))
        ));

        $this->add($confirmPassword);

        //CSRF
        $csrf = new Hidden('csrf');

        $csrf->addValidator(
            new Identical(array(
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF validation failed'
            ))
        );

        $this->add($csrf);
    }

    private function validateUsername($username)
    {
        if (preg_match("/^[a-z0-9]+$/", $str) == 1) {
            return false;
        }

        return true;
    }

    private function checkDuplicateEmail($email)
    {
        $member = Member::find([
            'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND email = "'. $email .'"'
        ]);

        if ($member->count() > 0) {
            return false;
        }

        return true;
    }

    private function checkDuplicateUsername($username)
    {
        $member = Member::find([
            'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND username = "'. $username .'"'
        ]);

        if ($member->count() > 0) {
            return false;
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
