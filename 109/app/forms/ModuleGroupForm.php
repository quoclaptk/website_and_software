<?php

namespace Modules\Forms;

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
use Phalcon\Forms\Element\File;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

class ModuleGroupForm extends Form
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

        $name = new Text(
            'name',
            [
                'placeholder' => 'Name'
            ]
        );

        $name->addValidators([
            new PresenceOf([
                'message' => 'Tên không được rỗng'
            ])
        ]);

        $this->add($name);


        $type = new Text(
            'type',
            [
                'placeholder' => 'Type'
            ]
        );

        $type->addValidators([
            new PresenceOf([
                'message' => 'Type không được rỗng'
            ])
        ]);

        $this->add($type);

        $link = new Text(
            'link',
            [
                'placeholder' => 'Link admin'
            ]
        );

        $this->add($link);

        $photo = new File('photo', array());

        $this->add($photo);

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
