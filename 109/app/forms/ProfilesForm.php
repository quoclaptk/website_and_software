<?php namespace Modules\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;

use Eduapps\Backend\Models\Profiles;

class ProfilesForm extends Form
{
    public function initialize($entity=null, $options=null)
    {
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
