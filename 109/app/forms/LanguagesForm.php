<?php

namespace Modules\Forms;

use Modules\Models\Languages;
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

class LanguagesForm extends Form
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
            'placeholder' => 'Name'
        ));

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Tên không được rỗng'
            ))
        ));

        if (!isset($options['edit'])) {
            $name->addValidators(array(
                new Uniqueness(array(
                    "model"   => new Languages(),
                    "attribute" => "name",
                    'message' => 'Tên ngôn ngữ đã tồn tại'
                ))
            ));
        }

        $this->add($name);

        $code = new Text('code', array(
            'placeholder' => 'Mã ngôn gữ'
        ));

        $code->addValidators(array(
            new PresenceOf(array(
                'message' => 'Mã ngôn ngữ không được rỗng'
            ))
        ));

        if (!isset($options['edit'])) {
            $code->addValidators(array(
                new Uniqueness(array(
                    "model"   => new Languages(),
                    "attribute" => "code",
                    'message' => 'Mã ngôn ngữ đã tồn tại'
                ))
            ));
        }

        $this->add($code);

        $sort = new Text('sort', array(
            'value' => '1'
        ));

        $sort->addValidators(array(
            new PresenceOf(array(
                'message' => 'Thứ tự không được rỗng'
            ))

        ));

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
