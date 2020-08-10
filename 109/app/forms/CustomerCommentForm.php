<?php

namespace Modules\Forms;

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
use Phalcon\Forms\Element\File;
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
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Hidden('id');
        }

        $this->add($id);

        $name = new Text('name', [
            'placeholder' => 'Họ tên'
        ]);
        $name->addValidators([
            new PresenceOf([
                'message' => 'Bạn phải nhập họ tên'
            ])
        ]);
        $this->add($name);

        $email = new Email('email', [
            'placeholder' => 'Email'
        ]);
        $this->add($email);
        

        $phone = new Text('phone', [
            'placeholder' => 'Số điện thoại'
        ]);
        if ($this->request->getPost('phone') != '') {
            $phone->addValidators([
                new Numericality([
                    'message' => 'Số điện thoại không đúng định dạng'
                ])
            ]);
        }
        $this->add($phone);

        $address = new Text('address', [
            'placeholder' => 'Địa chỉ'
        ]);
        $this->add($address);

        $subject = new Text('subject', [
            'placeholder' => 'Chủ đề'
        ]);
        $this->add($subject);

        $photo = new File('photo', array());
        $this->add($photo);

        $comment = new TextArea('comment', [
            "value" => "",
            'placeholder' => 'Nội dung',
            "rows" => 5
        ]);
        $this->add($comment);

        $sort = new Text('sort', [
            'value' => '1'
        ]);

        $sort->addValidators([
            new PresenceOf([
                'message' => 'Thứ tự không được rỗng'
            ])
        ]);

        $this->add($sort);

        $this->add(new Select(
            'active',
            [
                'Y' => 'Có',
                'N' => 'Không'
            ]
        ));
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
