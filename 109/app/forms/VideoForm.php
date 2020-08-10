<?php

namespace Modules\Forms;

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
use Phalcon\Validation\Validator\Confirmation;

class VideoForm extends Form
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

        $category = new Select('category_video_id', CategoryVideo::find('active = "Y"'), array(
            'using' => array('id', 'name'),
            //'useEmpty' => true,
        ));
        $category->addValidators(array(
            new PresenceOf(array(
                'message' => 'Bạn phải chọn danh mục'
            ))

        ));

        $this->add($category);

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Tên không được rỗng'
            ))

        ));

        $this->add($name);


        $title = new Text('title', array(
            'placeholder' => 'Title'
        ));

        $this->add($title);

        $keywords = new TextArea('keywords', array(
            "value" => "",
            "rows" => 5
        ));
        $this->add($keywords);

        $description = new TextArea('description', array(
            "value" => "",
            "rows" => 5
        ));
        $this->add($description);

        /*$file = new File('file', array(
            "value" => ""
        ));

        $this->add($file);*/

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
