<?php

namespace Modules\Forms;

use Modules\Models\FormGroup;
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

class FormGroupForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
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

        $module = new Text(
            'module',
            [
                'placeholder' => 'Tên module'
            ]
        );

        $module->addValidators([
            new PresenceOf([
                'message' => 'Tên module không được rỗng'
            ])
        ]);
        if ($options == null) {
            $module->addValidators([
                new Uniqueness([
                    "model"   => new FormGroup(),
                    "attribute" => "module",
                    'message' => 'Tên module đã tồn tại.'
                ])
            ]);
        }

        $this->add($module);

        $sort = new Text('sort', [
            'value' => '1'
        ]);

        $sort->addValidators([
            new PresenceOf([
                'message' => 'Thứ tự không được rỗng'
            ])
        ]);

        $this->add($sort);

        $this->add(new Select('active', array(
            'Y' => 'Có',
            'N' => 'Không'
        )));
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
