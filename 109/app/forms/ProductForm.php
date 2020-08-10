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

class ProductForm extends BaseForm
{
    public function initialize($entity = null, $options = null)
    {
        $tmpSubdomainLanguages = $this->getListLanguage();

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
            ])
        ]);
        $this->add($slug);

        $link = new Text('link', [
            'placeholder' => 'Link khác'
        ]);
        $this->add($link);

        $cartlink = new Text('cart_link', [
            'placeholder' => 'Link nút mua hàng'
        ]);
        $this->add($cartlink);

        $code = new Text(
            'code',
            [
                'placeholder' => 'Mã sản phẩm'
            ]
        );

        $this->add($code);

        $cost = new Text(
            'cost',
            [
                'placeholder' => 'Giá gốc'
            ]
        );

        $this->add($cost);

        $price = new Text(
            'price',
            [
                'placeholder' => 'Giá khuyến mãi'
            ]
        );

        $this->add($price);

        $cost_usd = new Text(
            'cost_usd'
        );

        $this->add($cost_usd);

        $price_usd = new Text(
            'price_usd'
        );

        $this->add($price_usd);

        $title = new Text(
            'title',
            [
                'placeholder' => 'Title'
            ]
        );

        $this->add($title);

        $keywords = new Text('keywords', [
            "placeholder" => "Keywords"
        ]);
        $this->add($keywords);

        $description = new Text('description', [
            "placeholder" => "Description"
        ]);
        $this->add($description);

        $photo= new File('photo', array());

        $this->add($photo);

        $photoSecondary= new File('photo_secondary', array());

        $this->add($photoSecondary);

        $summary = new TextArea('summary', [
            "value" => "",
            "rows" => 5
        ]);
        $this->add($summary);

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

        $sort = new Text('sort', [
            'value' => '1'
        ]);

        $sort->addValidators([
            new PresenceOf([
                'message' => 'Thứ tự không được rỗng'
            ])
        ]);
        $this->add($sort);

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

                    $link = new Text('link_' . $langCode, [
                        'placeholder' => 'Link khác'
                    ]);
                    $this->add($link);

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

                    $summary = new TextArea('summary_' . $langCode, [
                        "value" => "",
                        "rows" => 5
                    ]);
                    $this->add($summary);

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
            return $this->mainGlobal->validateUrlPageUpdate($id, $slug, "product");
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
