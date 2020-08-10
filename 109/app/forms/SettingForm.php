<?php

namespace Modules\Forms;

use Modules\Models\Layout;
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
use Phalcon\Forms\Element\Radio;
use Phalcon\Forms\Element\File;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\StringLength;
use Phalcon\Validation\Validator\Confirmation;

use Phalcon\Mvc\Model\Validator\Email as EmailValidator;

class SettingForm extends BaseForm
{
    public function initialize($entity = null, $options = null)
    {
        $tmpSubdomainLanguages = $this->getListLanguage();
        
        $name = new Text('name', array(
            'placeholder' => 'Name'
        ));

        $name->addValidators(array(
            new PresenceOf(array(
                'message' => 'Tên không được rỗng'
            ))

        ));
        $this->add($name);

        $slogan = new Text('slogan', array(
            'placeholder' => 'Slogan'
        ));

        $this->add($slogan);

        /*        $layout = new Radio('layout_id', Layout::find('active = "Y"'), array(
                    'using' => array('id', 'name'),
                    'useEmpty' => true,
                ));
                $this->add($layout);*/

        $address = new TextArea('address', array(
            'placeholder' => 'Địa chỉ',
            "value" => "",
            "rows" => 1
        ));

        $this->add($address);

        $email = new Text('email', array(
            'placeholder' => 'Email'
        ));

        if ($this->request->getPost('email') != '') {
            $email->addValidators([
                new Email(
                    [
                        'message' => 'Email không đúng định dạng',
                    ]
                )

            ]);
        }

        $this->add($email);

        $email = new Text('email_order', array(
            'placeholder' => 'Email nhận đơn hàng'
        ));

        if ($this->request->getPost('email_order') != '') {
            $email->addValidators([
                new Email(
                    [
                        'message' => 'Email không đúng định dạng',
                    ]
                )

            ]);
        }

        $this->add($email);

        $website = new Text('website', array(
            'placeholder' => 'Website'
        ));

        $this->add($website);

        $phone = new Text('phone', array(
            'placeholder' => 'Phone'
        ));
        $this->add($phone);

        $hotline = new TextArea('hotline', array(
            'placeholder' => 'Hotline',
            "value" => "",
            "rows" => 1
        ));
        $this->add($hotline);

        $fax = new Text('fax', array(
            'placeholder' => 'Fax'
        ));
        $this->add($fax);

        $business_license = new Text('business_license', array(
            'placeholder' => 'Thông tin giấy phép kinh doanh'
        ));
        $this->add($business_license);

        $tax_code = new Text('tax_code', array(
            'placeholder' => 'Mã số thuế'
        ));
        $this->add($tax_code);

        $copyright = new Text('copyright', array(
            'placeholder' => 'Copyright'
        ));
        $this->add($copyright);

        $facebook = new Text('facebook', array(
            'placeholder' => 'Facebook'
        ));
        $this->add($facebook);

        $google = new Text('google', array(
            'placeholder' => 'Google'
        ));
        $this->add($google);

        $twitter = new Text('twitter', array(
            'placeholder' => 'Twitter'
        ));
        $this->add($twitter);

        $youtube = new Text('youtube', array(
            'placeholder' => 'Youtube'
        ));
        $this->add($youtube);

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

        $analytics = new TextArea('analytics', array(
            "value" => "",
            "rows" => 5
        ));
        $this->add($analytics);

        $note_payment_method_2 = new TextArea('note_payment_method_2', array(
            "value" => "",
            "rows" => 2
        ));
        $this->add($note_payment_method_2);

        $head_content = new TextArea('head_content', array(
            "value" => "",
            "rows" => 2
        ));
        $this->add($head_content);

        $body_content = new TextArea('body_content', array(
            "value" => "",
            "rows" => 2
        ));
        $this->add($body_content);

        $logo = new File('logo', array());
        $this->add($logo);

        $favicon = new File('favicon', array());
        $this->add($favicon);

        $bgr_ycbg = new File('bgr_ycbg', array());
        $this->add($bgr_ycbg);

        $image_meta = new File('image_meta', array());
        $this->add($image_meta);

        $image_menu_2 = new File('image_menu_2', array());
        $this->add($image_menu_2);

        $banner_1 = new File('banner_1', array());
        $this->add($banner_1);

        $banner_2 = new File('banner_2', array());
        $this->add($banner_2);

        $banner_3 = new File('banner_3', array());
        $this->add($banner_3);

        $banner_4 = new File('banner_4', array());
        $this->add($banner_4);

        $article_home = new TextArea('article_home', array(
            "placeholder" => "Bài viết trang chủ",
            "value" => "",
            "rows" => 5
        ));
        $this->add($article_home);

        $contact = new TextArea('contact', array(
            "value" => "",
            "rows" => 5
        ));
        $this->add($contact);

        $footer = new TextArea('footer', array(
            "value" => "",
            "rows" => 5
        ));
        $this->add($footer);

        $order_admin_note = new TextArea('order_admin_note', array(
            "value" => "",
            "rows" => 5
        ));
        $this->add($order_admin_note);

        $mapCode = new TextArea('map_code', array(
            "placeholder" => "Mã nhúng bản đồ",
            "rows" => 5
        ));
        $this->add($mapCode);

        $youtubeCode = new TextArea('youtube_code', array(
            "placeholder" => "Mã nhúng youtube",
            "rows" => 5
        ));
        $this->add($youtubeCode);

        $enable_image_article_home = new Check('enable_image_article_home', array(
            'value' => '1'
        ));
        $this->add($enable_image_article_home);

        $enable_search_advance_article_home = new Check('enable_search_advance_article_home', array(
            'value' => '1'
        ));
        $this->add($enable_search_advance_article_home);

        $image_article_home = new File('image_article_home', array());
        $this->add($image_article_home);

        $logoTextUp = "";
        $logoTextDown = "";
        if (!empty($entity->text_logo)) {
            $textLogo = json_decode($entity->text_logo, true);
            ;
            if (!empty($textLogo)) {
                $logoTextUp = $textLogo[0];
                if (count($textLogo) > 1) {
                    $logoTextDown = $textLogo[1];
                }
            }
        }

        $logo_text_up = new Text('logo_text_up', [
            'value' => $logoTextUp
        ]);
        $this->add($logo_text_up);

        $logo_text_down = new Text('logo_text_down', [
            'value' => $logoTextDown
        ]);
        $this->add($logo_text_down);

        for ($i = 0; $i < 7; $i++) {
            if ($entity->enable_logo_text == $i) {
                $enable_logo_text_value = [
                    'value' => $i,
                    'checked' => 'checked'
                ];
            } else {
                $enable_logo_text_value = [
                    'value' => $i
                ];

                if ($i == 0) {
                    $enable_logo_text_value['checked'] = 'checked';
                }
            }
            
            $enable_logo_text = new Radio('enable_logo_text', $enable_logo_text_value);
            $this->add($enable_logo_text);
        }
        

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

                    $slogan = new Text('slogan_' . $langCode, array(
                        'placeholder' => 'Slogan'
                    ));

                    $this->add($slogan);

                    $address = new TextArea('address_' . $langCode, array(
                        'placeholder' => 'Địa chỉ',
                        "value" => "",
                        "rows" => 1
                    ));

                    $this->add($address);

                    $copyright = new Text('copyright_' . $langCode, array(
                        'placeholder' => 'Copyright'
                    ));
                    $this->add($copyright);

                    $business_license = new Text('business_license_' . $langCode, array(
                        'placeholder' => 'Thông tin giấy phép kinh doanh'
                    ));
                    $this->add($business_license);

                    $title = new Text('title_' . $langCode, array(
                        'placeholder' => 'Title'
                    ));
                    $this->add($title);

                    $keywords = new TextArea('keywords_' . $langCode, array(
                        "value" => "",
                        "rows" => 5
                    ));
                    $this->add($keywords);

                    $description = new TextArea('description_' . $langCode, array(
                        "value" => "",
                        "rows" => 5
                    ));
                    $this->add($description);

                    $note_payment_method_2 = new TextArea('note_payment_method_2_' . $langCode, array(
                        "value" => "",
                        "rows" => 5
                    ));
                    $this->add($note_payment_method_2);

                    $article_home = new TextArea('article_home_' . $langCode, array(
                        "placeholder" => "Bài viết trang chủ",
                        "value" => "",
                        "rows" => 5
                    ));
                    $this->add($article_home);

                    $contact = new TextArea('contact_' . $langCode, array(
                        "value" => "",
                        "rows" => 5
                    ));
                    $this->add($contact);

                    $footer = new TextArea('footer_' . $langCode, array(
                        "value" => "",
                        "rows" => 5
                    ));
                    $this->add($footer);

                    $logo = new File('logo_' . $langCode, array());
                    $this->add($logo);

                    for ($i = 0; $i < 7; $i++) {
                        if ($entity->enable_logo_text . '_' . $langCode == $i) {
                            $enable_logo_text_value = [
                                'value' => $i,
                                'checked' => 'checked'
                            ];
                        } else {
                            $enable_logo_text_value = [
                                'value' => $i
                            ];

                            if ($i == 0) {
                                $enable_logo_text_value['checked'] = 'checked';
                            }
                        }
                        
                        $enable_logo_text = new Radio('enable_logo_text_' . $langCode, $enable_logo_text_value);
                        $this->add($enable_logo_text);
                    }

                    $logoTextUp = "";
                    $logoTextDown = "";
                    if (!empty($entity->text_logo_lang[$langCode][0])) {
                        $logoTextUp = $entity->text_logo_lang[$langCode][0];
                    }

                    if (!empty($entity->text_logo_lang[$langCode][1])) {
                        $logoTextDown = $entity->text_logo_lang[$langCode][1];
                    }

                    $logo_text_up = new Text('logo_text_up_' . $langCode, [
                        'value' => $logoTextUp
                    ]);
                    $this->add($logo_text_up);

                    $logo_text_down = new Text('logo_text_down_' . $langCode, [
                        'value' => $logoTextDown
                    ]);
                    $this->add($logo_text_down);
                }
            }
        }
    }
}
