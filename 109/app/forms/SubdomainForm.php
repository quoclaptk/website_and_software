<?php

namespace Modules\Forms;

use Modules\Models\Subdomain;
use Modules\Models\Users;
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Alpha;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Uniqueness;

class SubdomainForm extends Form
{
    public function initialize($entity = null, $options = null)
    {

        //In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Text('id');
        }

        $this->add($id);

        $name = new Text('name', array(
            'placeholder' => 'Tên domain(Viết liền không dấu)'
        ));

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Tên domain không được rỗng'
            )),
            new Regex(array(
                "pattern" => "/^[a-z-0-9]+$/",
                'message' => 'Tên domain không đúng định dạng'
            )),
            new Uniqueness(array(
                "model"   => new Subdomain(),
                "attribute" => "name",
                'message' => 'Tên domain đã tồn tại. Vui lòng tạo domain khác.'
            ))

        ));

        $this->add($name);

        $username = new Text('username', array(
            'placeholder' => 'Tài khoản(Viết liền không dấu)'
        ));

        $username->addValidators(array(
            new PresenceOf(array(
                'message' => 'Username không được rỗng'
            )),
            new Regex(array(
                "pattern" => "/^[a-z-0-9]+$/",
                'message' => 'Username không đúng định dạng'
            )),
            new Uniqueness(array(
                "model"   => new Users(),
                "attribute" => "username",
                'message' => 'Username đã tồn tại. Vui lòng tạo username khác.'
            ))

        ));

        $this->add($username);

        $password = new Text('password', array(
            'placeholder' => 'Mật khẩu'
        ));

        $password->addValidators(array(
            new PresenceOf(array(
                'message' => 'Bạn chưa nhập mật khẩu'
            )),
            /*new Confirmation(array(
                'message' => 'Mật khẩu xác nhận không khớp.',
                'with' => 'confirmPassword'
            ))*/
        ));

        //$password->clear();

        $this->add($password);

        //Confirm Password
        /*$confirmPassword = new Text('confirmPassword', array(
            'placeholder' => 'Xác nhận mật khâu'
        ));


        $confirmPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'Bạn chưa nhập mật khẩu xác nhận'
            ))
        ));

        $this->add($confirmPassword);*/
    }

    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
