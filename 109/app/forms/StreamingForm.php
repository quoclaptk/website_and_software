<?php

namespace Modules\Forms;

use Modules\Models\Leagues;
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

class StreamingForm extends Form
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


        $leagues = new Select('league_id', Leagues::find('active = "Y"'), array(
            'using' => array('id', 'name'),
            'useEmpty' => true,
        ));
        $leagues->addValidators(array(
            new PresenceOf(array(
                'message' => 'Bạn phải chọn giải đấu'
            ))

        ));

        $this->add($leagues);

        $house_team = new Text('house_team', array(
            'placeholder' => ''
        ));
        $house_team->addValidators(array(
            new PresenceOf(array(
                'message' => 'Trường dữ liệu yêu cầu'
            ))

        ));
        $this->add($house_team);

        $guest_team = new Text('guest_team', array(
            'placeholder' => ''
        ));
        $guest_team->addValidators(array(
            new PresenceOf(array(
                'message' => 'Trường dữ liệu yêu cầu'
            ))

        ));
        $this->add($guest_team);

        $house_logo = new File('house_logo', array());
        $this->add($house_logo);

        $guest_logo = new File('guest_logo', array());
        $this->add($guest_logo);

        $day = new Text('day', array(
            'placeholder' => ''
        ));
        $day->addValidators(array(
            new PresenceOf(array(
                'message' => 'Trường dữ liệu yêu cầu'
            ))

        ));
        $this->add($day);

        //		$iframe = new TextArea('iframe',array(
        //			"value" => "",
        //			"rows" => 5
        //		));
        //		$this->add($iframe);

        $link_sopcast = new Text('link_sopcast', array(
            'placeholder' => ''
        ));
        $this->add($link_sopcast);

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
