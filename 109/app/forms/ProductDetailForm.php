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

class ProductDetailForm extends BaseForm
{
    public function initialize($entity = null, $options = null)
    {
        $tmpSubdomainLanguages = $this->getListLanguage();

        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Text('id');
        }

        $this->add($id);

        $name = new Text(
            'name',
            [
                'placeholder' => 'Tên'
            ]
        );

        $name->addValidators([
            new PresenceOf([
                'message' => 'Tên không được rỗng'
            ])
        ]);

        $this->add($name);

        $sort = new Text('sort', [
            'value' => '1'
        ]);

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

        if (count($tmpSubdomainLanguages) > 0) {
            foreach ($tmpSubdomainLanguages as $tmp) {
                $langName = $tmp->language->name;
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $name = new Text('name_' . $langCode, array(
                        'placeholder' => 'Tên'
                    ));

                    $name->addValidators(array(
                        new PresenceOf(array(
                            'message' => 'Tên không được rỗng'
                        ))

                    ));
                    $this->add($name);
                }
            }
        }
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
