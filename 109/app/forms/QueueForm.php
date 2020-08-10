<?php

namespace Modules\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Validation\Validator\PresenceOf;

class QueueForm extends Form
{
 public function initialize($entity = null, $options = null)
    {
    	$name = new Text(
            'name',
            [
                'placeholder' => 'To'
            ]
        );
        $name->addValidators([
            new PresenceOf([
                'message' => 'Mail to is vaild'
            ])
        ]);
        $this->add($name);

        $subject = new Text(
            'subject',
            [
                'placeholder' => 'Subject'
            ]
        );
        $subject->addValidators([
            new PresenceOf([
                'message' => 'subject is vaild'
            ])
        ]);

        $this->add($subject);

        $content = new Text(
            'content',
            [
                'placeholder' => 'Contend'
            ]
        );
        $content->addValidators([
            new PresenceOf([
                'message' => 'Content is vaild'
            ])
        ]);

        $this->add($content);
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