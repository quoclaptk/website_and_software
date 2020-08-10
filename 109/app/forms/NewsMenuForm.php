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
use Phalcon\Validation\Validator\Callback;

class NewsMenuForm extends BaseForm
{
    public function initialize($entity = null, $options = null)
    {
        $tmpSubdomainLanguages = $this->getListLanguage();

        //In edition the id is hidden
        if (isset($options['edit']) && $options['edit']) {
            $id = new Hidden('id');
            if ($entity->active == 'Y') {
                $activeValue = [
                    'value' => 'Y',
                    'checked' => 'checked'
                ];
            } else {
                $activeValue = [
                    'value' => 'Y'
                ];
            }
        } else {
            $id = new Hidden('id');
            $activeValue = [
                'value' => 'Y',
                'checked' => 'checked'
            ];
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
            ])
        ]);
        $this->add($slug);

        $summary = new Text('summary', [
            'placeholder' => 'Tóm tắt'
        ]);
        $this->add($summary);

        $title = new Text('title', [
            'placeholder' => 'Title'
        ]);
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

        $font_class = new Text(
            'font_class',
            [
                'placeholder' => 'Tên font'
            ]
        );
        $this->add($font_class);

        $icon = new File('icon', array());
        $this->add($icon);

        $sort = new Text('sort', [
            'value' => '1'
        ]);

        $sort->addValidators([
            new PresenceOf([
                'message' => 'Thứ tự không được rỗng'
            ])
        ]);

        $this->add($sort);
     
        $menu = new Check('menu', array(
            'value' => 'Y'
        ));
        $this->add($menu);

        $regForm = new Check('reg_form', array(
            'value' => 'Y'
        ));
        $this->add($regForm);

        $messengerForm = new Check('messenger_form', array(
            'value' => 'Y'
        ));
        $this->add($messengerForm);

        $active = new Check('active', $activeValue);
        $this->add($active);

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

                    $slug = new Text('slug_' . $langCode, [
                        'placeholder' => 'Url'
                    ]);
                    $slug->addValidators([
                        new PresenceOf([
                            'message' => 'Đường dẫn không được rỗng'
                        ]),
                        new Callback([
                            'message' => 'Đường dẫn không được chứa các ký tự (@#$^*|)',
                            "callback" => function () use ($langCode) {
                                $slugInput = $this->request->getPost('slug_' . $langCode);
                                if (strpos($slugInput, '|') !== false || strpos($slugInput, '@') !== false || strpos($slugInput, '#') !== false || strpos($slugInput, '$') !== false || strpos($slugInput, '^') !== false || strpos($slugInput, '*') !== false) {
                                    return false;
                                }

                                return true;
                            }
                        ])
                    ]);
                    $this->add($slug);

                    $summary = new Text('summary_' . $langCode, [
                        'placeholder' => 'Tóm tắt'
                    ]);
                    $this->add($summary);

                    $title = new Text('title_' . $langCode, [
                        'placeholder' => 'Title'
                    ]);
                    $this->add($title);

                    $keywords = new TextArea('keywords_' . $langCode, [
                        "value" => "",
                        "rows" => 4
                    ]);
                    $this->add($keywords);

                    $description = new TextArea('description_' . $langCode, [
                        "value" => "",
                        "rows" => 4
                    ]);

                    $this->add($description);

                    $content = new TextArea('content_' . $langCode, [
                        "value" => "",
                        "rows" => 5
                    ]);
                    $this->add($content);
                }
            }
        }
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
            return $this->mainGlobal->validateUrlPageUpdate($id, $slug, "news_menu");
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
