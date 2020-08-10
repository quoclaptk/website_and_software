<?php

namespace Modules\Forms;

use Modules\Models\ConfigCore;
use Modules\Models\ConfigGroup;
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

class ConfigCoreForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Text('id');
        }
        $this->add($id);

        $config_group = new Select('config_group_id', ConfigGroup::find('active = "Y"'), array(
            'using' => array('id', 'name'),
            'useEmpty' => true,
        ));
        $config_group->addValidators(array(
            new PresenceOf(array(
                'message' => 'Bạn phải chọn nhóm cấu hình'
            ))

        ));

        $this->add($config_group);

        $name = new Text(
            'name',
            [
                'placeholder' => 'Tên'
            ]
        );
        $name->addValidators([
            new PresenceOf([
                'message' => 'Tên không được rỗng'
            ])
        ]);
        $this->add($name);

        $field = new Text(
            'field',
            [
                'placeholder' => 'Tên field'
            ]
        );
        $field->addValidators([
            new PresenceOf([
                'message' => 'Tên field không được rỗng'
            ])
        ]);
        if ($options == null) {
            $field->addValidators([
                new Uniqueness([
                    "model"   => new ConfigCore(),
                    "attribute" => "field",
                    'message' => 'Tên field đã tồn tại.'
                ])
            ]);
        }
        $this->add($field);

        $value = new TextArea(
            'value',
            [
                "value" => "",
                "rows" => 4,
                'placeholder' => 'Giá trị mặc định'
            ]
        );
        $this->add($value);

        $min_value = new Text(
            'min_value',
            [
                'placeholder' => 'Giá trị nhỏ nhất'
            ]
        );
        $this->add($min_value);

        $max_value = new Text(
            'max_value',
            [
                'placeholder' => 'Giá trị lớn nhất'
            ]
        );
        $this->add($max_value);

        $description = new TextArea('description', array(
            "value" => "",
            "rows" => 4
        ));
        $this->add($description);

        $guide = new TextArea('guide', array(
            "value" => "",
            "id" => null,
            "rows" => 4
        ));
        $this->add($guide);

        $this->add(new Select('type', array(
            'text' => 'Text',
            'email' => 'Email',
            'checkbox' => 'Checkbox',
            'select' => 'Select',
            'radio' => 'Radio',
            'textarea' => 'Textarea',
        )));

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
