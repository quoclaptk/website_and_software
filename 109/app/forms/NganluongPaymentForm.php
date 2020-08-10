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
use Phalcon\Forms\Element\File;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Numericality;
use Phalcon\Validation\Validator\Callback;
use Phalcon\Validation\Validator\Alpha as AlphaValidator;

class NganluongPaymentForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        $amount = new Text('amount', [
            'placeholder' => 'Số tiền(tối thiểu 50.000)'
        ]);

        $amount->addValidators([
            new PresenceOf([
                'message' => 'Bạn chưa nhập số tiền !'
            ]),
            new Numericality([
                'message' => 'Định dạng không đúng. Bạn phải nhập số !'
            ]),
            new Callback(
                [
                    "message" => "Số tiền nạp tối thiểu là 50.000",
                    "callback" => function () {
                        if (!$this->checkoutMinAmount($this->request->getPost('amount'))) {
                            return false;
                        }
                        return true;
                    }
                ]
            )
        ]);
        
        $this->add($amount);
    }

    private function checkoutMinAmount($amount)
    {
        return $amount < 50000 ? false : true;
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
