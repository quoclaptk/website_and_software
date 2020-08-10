<?php

namespace Modules\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;

class AuthForm extends Form
{
 public function initialize($entity = null, $options = null)
    {
    	$name = new Text(
            'name',
            [
                'placeholder' => 'Giá Trị'
            ]
        );
        $name->addValidators([
            new PresenceOf([
                'message' => 'Key không được rỗng'
            ])
        ]);
        $this->add($name);

        $word_key = new Text(
            'word_key',
            [
                'placeholder' => 'Từ khóa mặc định'
            ]
        );
        $word_key->addValidators([
            new PresenceOf([
                'message' => 'Trường không được rỗng'
            ])
        ]);

        $this->add($word_key);
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