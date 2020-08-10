<?php

namespace Modules\Forms\Frontend;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Uniqueness;

class CustomerCommentForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        $name = new Text('cc_f_name', [
            'placeholder' => 'Họ tên'
        ]);
        $name->addValidators([
            new PresenceOf([
                'message' => 'Bạn phải nhập họ tên'
            ])
        ]);
        $this->add($name);

        $email = new Email('cc_f_email', [
            'placeholder' => 'Email'
        ]);
        $this->add($email);

        $phone = new Text('cc_f_phone', [
            'placeholder' => 'Số điện thoại'
        ]);
        $phone->addValidators([
            new Numericality([
                'message' => 'Số điện thoại không đúng định dạng'
            ])
        ]);
        $this->add($phone);

        $address = new Text('cc_f_address', [
            'placeholder' => 'Địa chỉ'
        ]);
        $address->addValidators([
            new PresenceOf([
                'message' => 'Bạn phải nhập địa chỉ'
            ])
        ]);
        $this->add($address);

        $subject = new Text('cc_f_subject', [
            'placeholder' => 'Chủ đề'
        ]);
        $this->add($subject);

        $comment = new TextArea('cc_f_comment', [
            "value" => "",
            'placeholder' => 'Ghi chú',
            "rows" => 5
        ]);
        $this->add($comment);
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
