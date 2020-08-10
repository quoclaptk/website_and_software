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

class UrlConfigForm extends Form
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

        $name = new Text('name', array(
            'placeholder' => 'Name'
        ));

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Tên không được rỗng'
            ))

        ));

        $this->add($name);

        $link = new Text('link', array(
            'placeholder' => 'link'
        ));

        $link->addValidators(array(
            new PresenceOf(array(
                'message' => 'Link không được rỗng'
            ))

        ));

        $this->add($link);

        $wrapper = new Text('wrapper', array(
            'placeholder' => 'wrapper'
        ));

        $wrapper->addValidators(array(
            new PresenceOf(array(
                'message' => 'Thẻ bao không được rỗng'
            ))

        ));

        $this->add($wrapper);

        $title = new Text('title', array(
            'placeholder' => 'Title'
        ));

        $title->addValidators(array(
            new PresenceOf(array(
                'message' => 'Thẻ title không được rỗng'
            ))

        ));

        $this->add($title);

        $summary = new Text('summary', array(
            'placeholder' => 'Summary'
        ));

        $summary->addValidators(array(
            new PresenceOf(array(
                'message' => 'Thẻ mô tả không được rỗng'
            ))
        ));

        $this->add($summary);

        $img = new Text('img', array(
            'placeholder' => 'Img'
        ));

        $img->addValidators(array(
            new PresenceOf(array(
                'message' => 'Thẻ hình ảnh không được rỗng'
            ))

        ));

        $this->add($img);

        $img_link_replace= new Text('img_link_replace', array(
            'placeholder' => 'Imglinkreplace'
        ));

        $this->add($img_link_replace);

        $href = new Text('href', array(
            'placeholder' => 'Href'
        ));

        $href->addValidators(array(
            new PresenceOf(array(
                'message' => 'Thẻ link không được rỗng'
            ))
        ));

        $this->add($href);

        $content = new Text('content', array(
            'placeholder' => 'Content'
        ));

        $content->addValidators(array(
            new PresenceOf(array(
                'message' => 'Thẻ nội dung không được rỗng'
            ))
        ));

        $this->add($content);


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
