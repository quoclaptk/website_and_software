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
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Numericality;

class SupportForm extends Form
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

        $this->add($name);

        $phone = new Text('phone', array(
            'placeholder' => 'Số điện thoại'
        ));

        /*$phone->addValidators(array(
            new PresenceOf(array(
                'message' => 'Số điện thoại không được rỗng'
            ))
        ));*/

        $phone->addValidators(array(
            new Numericality(array(
                'message' => 'Số điện thoại không đúng định dạng.'
            ))
        ));

        $this->add($phone);

        $email = new Email('email', array(
            'placeholder' => 'Email'
        ));

        /*$email->addValidators(array(
            new EmailValidator(array(
                'message' => 'Email không đúng định dạng.'
            ))
        ));*/

        $this->add($email);

        $skype = new Text('skype', array(
            'placeholder' => 'Skype'
        ));
        $this->add($skype);

        $zalo = new Text('zalo', array(
            'placeholder' => 'Zalo'
        ));
        $this->add($zalo);

        $messenger = new Text('messenger', array(
            'placeholder' => 'Messenger(Facebook)'
        ));
        $this->add($messenger);

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
