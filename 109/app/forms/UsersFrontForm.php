<?php

namespace Modules\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

use Eduapps\Frontend\Models\Profiles;

/**
 * Users customer is Frontend
 */
class UsersFrontForm extends Form
{
    public function initialize($entity=null, $options=null)
    {

        //In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Text('id');
        }

        $this->add($id);

        $this->add(new Text('username'));

        $this->add(new Text('email'));

        $this->add(new Select('profilesId', Profiles::find('active = "Y"'), array(
            'using' => array('id', 'name'),
            'useEmpty' => true,
            'emptyText' => '...',
            'emptyValue' => ''
        )));

        $this->add(new Select('banned', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));

        $this->add(new Select('suspended', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));

        $this->add(new Select('active', array(
            'Y' => 'Yes',
            'N' => 'No'
        )));
    }
}
