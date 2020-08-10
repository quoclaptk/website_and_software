<?php

namespace Modules\Forms\Frontend;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

use Modules\Models\Profiles;

class ChangePasswordForm extends Form
{
    public function initialize()
    {
        //Password
        $password = new Password('password');

        $password->addValidators(array(
            new PresenceOf(array(
                    'message' => 'Bạn chưa nhập mật khẩu'
            )),
            /*new StringLength(array(
                    'min' => 8,
                    'messageMinimum' => 'Mật khẩu quá ngắn. Tối thiểu 8 ký tự.'
            )),*/
            new Confirmation(array(
                    'message' => 'Mật khẩu xác nhận không khớp.',
                    'with' => 'confirmPassword'
            ))
        ));

        $this->add($password);

        //Confirm Password
        $confirmPassword = new Password('confirmPassword');

        $confirmPassword->addValidators(array(
            new PresenceOf(array(
                'message' => 'Bạn chưa nhập mật khẩu xác nhận'
            ))
        ));

        $this->add($confirmPassword);
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
