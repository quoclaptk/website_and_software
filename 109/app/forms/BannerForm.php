<?php

namespace Modules\Forms;

use Modules\Models\BannerType;
use Modules\Models\CategoryVideo;
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
use Phalcon\Validation\Validator\File as FileValidator;
use Phalcon\Validation\Validator\Confirmation;

class BannerForm extends BaseForm
{
    public function initialize($entity = null, $options = null)
    {
        $tmpSubdomainLanguages = $this->getListLanguage();
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


        $this->add($name);

        $link = new Text('link', array(
            'placeholder' => 'Link'
        ));

        $this->add($link);

        $photo = new File('photo', array());

        $this->add($photo);

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

        if (count($tmpSubdomainLanguages) > 0) {
            foreach ($tmpSubdomainLanguages as $tmp) {
                $langName = $tmp->language->name;
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $name = new Text('name_' . $langCode, array(
                        'placeholder' => 'Tên'
                    ));

                    $this->add($name);

                    $link = new Text('link_' . $langCode, [
                        'placeholder' => 'Tóm tắt'
                    ]);
                    $this->add($link);

                    $photo = new File('photo_' . $langCode, array());
                    $this->add($photo);
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
