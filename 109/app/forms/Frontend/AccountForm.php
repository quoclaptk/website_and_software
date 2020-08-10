<?php

namespace Modules\Forms\Frontend;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Date;
use Phalcon\Forms\Element\Numeric;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Alpha as AlphaValidator;
use Phalcon\Validation\Validator\Callback;
use Modules\Models\Member;
use Modules\PhalconVn\MainGlobal;

class AccountForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        $mainGlobal = new MainGlobal();
        $this->_subdomain_id = $mainGlobal->getDomainId();

        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Hidden('id');
        }
        
        $fullName = new Text('fullName', array('placeholder' =>'Họ tên'));
        $this->add($fullName);

        $username = new Text('username', array('placeholder' =>'Username'));
        $this->add($username);
                
        $email = new Text('email', array('placeholder'=>'Email'));
        $this->add($email);

        $address = new Text('address', array('placeholder' =>'Địa chỉ'));
        $this->add($address);

        $phone = new Text('phone', [
            'placeholder' => 'Số điện thoại',
            'id' => null
        ]);

        if ($this->request->getPost('phone') != '') {
            $phone->addValidators([
                new Numericality([
                    'message' => 'Số điện thoại không đúng định dạng'
                ])
            ]);
        }
        $this->add($phone);
        
        //CSRF
        $csrf = new Hidden('csrf');
        $csrf->addValidator(
            new Identical(array(
                'value' => $this->security->getSessionToken(),
                'message' => 'CSRF validation failed'
            ))
        );

        $this->add($csrf);
    }
    
    public function messages($username)
    {
        if ($this->hasMessagesFor($username)) {
            foreach ($this->getMessagesFor($username) as $message) {
                $this->flash->error($message);
            }
        }
    }
}
