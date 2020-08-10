<?php

namespace Modules\Forms;

use Modules\Models\NewsCategory;
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

use Phalcon\Validation\Validator\Callback;

class NewsCategoryForm extends Form
{
    public function initialize($entity = null, $options = null)
    {
        //In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
        } else {
            $id = new Hidden('id');
        }

        $this->add($id);

        $name = new Text('name', [
            'placeholder' => 'Name'
        ]);
        $name->addValidators([
            new PresenceOf([
                'message' => 'Tên không được rỗng'
            ])
        ]);
        $this->add($name);

        $slug = new Text('slug', [
            'placeholder' => 'Url'
        ]);
        $slug->addValidators([
            new PresenceOf([
                'message' => 'Đường dẫn không được rỗng'
            ]),
            new Callback([
                'message' => 'Đường dẫn không được chứa các ký tự (@#$^*|)',
                "callback" => function () {
                    if (strpos($this->request->getPost('slug'), '|') !== false || strpos($this->request->getPost('slug'), '@') !== false || strpos($this->request->getPost('slug'), '#') !== false || strpos($this->request->getPost('slug'), '$') !== false || strpos($this->request->getPost('slug'), '^') !== false || strpos($this->request->getPost('slug'), '*') !== false) {
                        return false;
                    }

                    return true;
                }
            ]),
            new Callback(
                [
                    "message" => "Đường dẫn đã tồn tại",
                    "callback" => function () {
                        if ($this->request->getPost('id') != "") {
                            if (!$this->checkDuplicateUrlUpdate($this->request->getPost('id'), $this->request->getPost('slug'))) {
                                return false;
                            }
                        } else {
                            if (!$this->checkDuplicateUrl($this->request->getPost('slug'))) {
                                return false;
                            }
                        }

                        return true;
                    }
                ]
            )
        ]);
        $this->add($slug);

        $title = new Text(
            'title',
            [
                'placeholder' => 'Title'
            ]
        );

        $this->add($title);

        $keywords = new TextArea('keywords', [
            "value" => "",
            "rows" => 4
        ]);
        $this->add($keywords);

        $description = new TextArea('description', [
            "value" => "",
            "rows" => 4
        ]);
        $this->add($description);

        $content = new TextArea('content', [
            "value" => "",
            "rows" => 5
        ]);
        $this->add($content);

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
            'menu',
            [
                'N' => 'Không',
                'Y' => 'Có'
            ]
        ));

        $this->add(new Select(
            'static',
            [
                'N' => 'Không',
                'Y' => 'Có'
            ]
        ));

        $this->add(new Select(
            'active',
            [
                'Y' => 'Có',
                'N' => 'Không'
            ]
        ));
    }

    private function checkDuplicateUrl($slug)
    {
        if ($slug == "") {
            return true;
        } else {
            return $this->mainGlobal->validateUrlPageCreate($slug);
        }
    }

    private function checkDuplicateUrlUpdate($id, $slug)
    {
        if ($slug == "") {
            return true;
        } else {
            return $this->mainGlobal->validateUrlPageUpdate($id, $slug, "news_category");
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
