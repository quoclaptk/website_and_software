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
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

use Modules\Models\Profiles;

class UsersForm extends Form
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

        $username = new Text('username', array(
            'placeholder' => 'Username'
        ));

        $username->addValidators(array(
            new PresenceOf(array(
                'message' => 'Username không được rỗng'
            )),

        ));

        $this->add($username);

        $email = new Text('email', array(
            'placeholder' => 'Email'
        ));

        $email->addValidators(array(
                    new PresenceOf(array(
                            'message' => 'Email không được rỗng'
                    ))

        ));
                
        $email->addValidators(array(
                    new Email(array(
                        'message' => 'Email không đúng định dạng'
                    ))

        ));

        $this->add($email);

        $profilesId = new Select(
            'profilesId',
            Profiles::find(array(
                            "conditions" => "id != 1 AND active = 'Y'",
                            "order" => "sort ASC, id DESC"
            )),
            array(
            'using' => array('id', 'name'),
            'useEmpty' => true,
        )
        );
        $profilesId->addValidators(array(
                    new PresenceOf(array(
                            'message' => 'Bạn phải chọn Profiles'
                    ))

        ));

        $this->add($profilesId);
                
        $password = new Password('password');

        $password->addValidators(array(
            new PresenceOf(array(
                    'message' => 'Bạn chưa nhập mật khẩu'
            )),
            new StringLength(array(
                    'min' => 8,
                    'messageMinimum' => 'Mật khẩu quá ngắn. Tối thiểu 8 ký tự.'
            )),
            new Confirmation(array(
                    'message' => 'Mật khẩu xác nhận không khớp.',
                    'with' => 'confirmPassword'
            ))
        ));

        $this->add($password);

        //Confirm Password
        $confirmPassword = new Password('confirmPassword');

        $confirmPassword->addValidators(array(
                new PresenceOf(array(
                        'message' => 'The confirmation password is required'
                ))
        ));

        $this->add($confirmPassword);

        $sort = new Text('sort', array(
            'value' => '1'
        ));

        $sort->addValidators(array(
            new PresenceOf(array(
                'message' => 'Thứ tự không được rỗng'
            ))

        ));

        $this->add($sort);

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


    public function messages($name)
    {
        if ($this->hasMessagesFor($name)) {
            foreach ($this->getMessagesFor($name) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
