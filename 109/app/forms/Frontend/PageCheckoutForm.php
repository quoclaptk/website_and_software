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

class PageCheckoutForm extends BaseFrontendForm
{
    public function initialize($entity = null, $options = null)
    {
        $messages = $this->getMessageLanguage();
        $name = new Text('name', [
            'placeholder' => 'Họ tên'
        ]);
        $name->addValidators([
            new PresenceOf([
                'message' => $messages->_('name_is_required')
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
        $phone->addValidators([
            new Numericality([
                'message' => $messages->_('phone_not_in_correct_format')
            ])
        ]);
        $this->add($phone);

        $address = new Text('address', [
            'placeholder' => 'Địa chỉ'
        ]);
        $address->addValidators([
            new PresenceOf([
                'message' => $messages->_('address_is_required')
            ])
        ]);
        $this->add($address);

        $comment = new TextArea('comment', [
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
