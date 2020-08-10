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

class ChannelForm extends Form
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

        $type = new Select('type', array(
            '1' => 'Bóng đá việt',
            '2' => 'Youtube',
            '3' => 'Talk TV',
        ));
        $this->add($type);

        $name = new Text('name', array(
            'placeholder' => ''
        ));
        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Trường dữ liệu yêu cầu'
            ))

        ));
        $this->add($name);

        $bitrate = new Text('bitrate', array(
            'placeholder' => ''
        ));
        $bitrate->addValidators(array(
            new PresenceOf(array(
                'message' => 'Trường dữ liệu yêu cầu'
            ))

        ));
        $this->add($bitrate);

        $iframe = new TextArea('iframe', array(
            "value" => "",
            "rows" => 20
        ));
        $this->add($iframe);

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
            'Y' => 'Yes',
            'N' => 'No'
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
