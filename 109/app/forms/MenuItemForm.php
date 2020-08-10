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
use Phalcon\Validation\Validator\Url;

class MenuItemForm extends Form
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


        $url = new Text(
            'url',
            [
                'placeholder' => 'Url'
            ]
        );

        $url->addValidators([
            new PresenceOf([
                'message' => 'Url không được rỗng'
            ])
        ]);

        $this->add($url);
        
        $other_url = new Text(
            'other_url',
            [
                'placeholder' => 'Link khác'
            ]
        );
        if ($this->request->getPost('other_url')) {
            $other_url->addValidators([
                new Url([
                    'message' => 'Định dạng link không đúng(Vd: http://...)'
                ])
            ]);
        }
            
        $this->add($other_url);

        $font_class = new Text(
            'font_class',
            [
                'placeholder' => 'Tên font'
            ]
        );
        $this->add($font_class);

        $photo= new File('photo', array());
        $this->add($photo);

        $sort = new Text('sort', [
            'value' => '1'
        ]);

        $this->add(new Select(
            'new_blank',
            [
                'N' => 'Không',
                'Y' => 'Có'
            ]
        ));

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
