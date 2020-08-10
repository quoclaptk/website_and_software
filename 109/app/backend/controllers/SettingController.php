<?php

namespace Modules\Backend\Controllers;

use Modules\Models\Background;
use Modules\Models\ConfigGroup;
use Modules\Models\ConfigCore;
use Modules\Models\ConfigItem;
use Modules\Models\Layout;
use Modules\Models\LayoutConfig;
use Modules\Models\ModuleGroup;
use Modules\Models\ModuleItem;
use Modules\Models\Subdomain;
use Modules\Models\TmpLayoutModule;
use Modules\Models\TmpModuleGroupLayout;
use Modules\Models\BannerType;
use Modules\Models\Menu;
use Modules\Models\MenuItem;
use Modules\Models\Posts;
use Modules\Models\Position;
use Modules\Models\BannerHtml;
use Modules\Models\Languages;
use Modules\Models\TmpSubdomainLanguage;
use Modules\Models\Users;
use Modules\Models\IpNote;
use Modules\Models\ProductDetail;
use Modules\Models\Category;
use Modules\Models\NewsMenu;
use Modules\Models\Banner;
use Modules\Models\TmpBannerBannerType;
use Modules\Models\TmpTypeModule;
use Modules\PhalconVn\General;
use Modules\Helpers\Helper;

use Modules\Models\Setting;
use Modules\Forms\SettingForm;

use Phalcon\Tag;
use Phalcon\Mvc\Model\Criteria;
use Phalcon\Paginator\Adapter\Model as Paginator;
use Phalcon\Text;
use Phalcon\Security\Random;
use Phalcon\Image\Adapter\GD;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\Url;
use Phalcon\Mvc\Model\Query;
use MatthiasMullie\Minify;

class SettingController extends BaseController
{
    public function onConstruct()
    {
        $this->_message = $this->getMessage();
        $this->view->module_name = 'Thiết lập';
    }

    /**
     * Setting content
     * 
     * @return View
     */
    public function contentAction()
    {
        $identity = $this->auth->getIdentity();
        $setting = Setting::findFirst(array(
            'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1'
        ));

        $helper = new Helper();
        // get current day
        $currentDate = date('Y-m-d');
        // get future days
        $futureDate = date('Y-m-d', strtotime('+1 year'));
        // call function date in helper file
        $dates = $helper->getDatesFromRange($currentDate, $futureDate);
               
        if (count($this->_tmpSubdomainLanguages) > 0) {
            $settingFormData = $setting->toArray();
            $row_id_lang = [];
            $imgUploadArticleHomeLangPaths = [];
            $settingLangData = [];
            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langCode = $tmp->language->code;
                if ($langCode != 'vi') {
                    $settingLang = Setting::findFirst(array(
                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $tmp->language_id .''
                    ));

                    if ($settingLang) {
                        $row_id_lang[$langCode] = $settingLang->row_id;
                        $settingLangData[$langCode] = $settingLang;
                        $settingLang = $settingLang->toArray();
                        $settingLangKeys = array_keys($settingLang);
                        foreach ($settingLangKeys as $settingLangKey) {
                            $settingFormData[$settingLangKey . '_' . $langCode] = $settingLang[$settingLangKey];
                        }

                        if (!empty($settingFormData['text_logo_' . $langCode])) {
                            $textLogo = json_decode($settingFormData['text_logo_' . $langCode], true);
                            ;
                            if (!empty($textLogo)) {
                                $settingFormData['text_logo_lang'][$langCode][0] =  $textLogo[0];
                                $logoTextUp = $textLogo[0];
                                if (count($textLogo) > 1) {
                                    $settingFormData['text_logo_lang'][$langCode][1] = $textLogo[1];
                                }
                            }
                        }
                    } else {
                        $random = new Random();
                        if ($this->cookies->has('row_id_setting_' . $langCode . '_' . $this->_get_subdomainID())) {
                            // Get the cookie
                            $rowIdCookie = $this->cookies->get('row_id_setting_' . $langCode . '_' . $this->_get_subdomainID());

                            // Get the cookie's value
                            $row_id = $rowIdCookie->getValue();
                        } else {
                            $row_id = $random->hex(10);
                            $this->cookies->set(
                                'row_id_setting_' . $langCode . '_' . $this->_get_subdomainID(),
                                $row_id,
                                time() + ROW_ID_COOKIE_TIME
                            );
                        }

                        $row_id_lang[$langCode] = $row_id;
                    }

                    //article home
                    $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/article_home/'. $row_id_lang[$langCode];
                    $dir = DOCUMENT_ROOT . '/public/' . $folderImg;
                    $imgUploadArticleHomeLangPaths[$langCode] = [];
                    if (is_dir($dir)) {
                        $imgUploads = array_filter(scandir($dir), function ($item) {
                            return ($item[0] !== '.');
                        });

                        if (!empty($imgUploads)) {
                            foreach ($imgUploads as $img) {
                                if ($img != 'medium') {
                                    $imgUploadArticleHomeLangPaths[$langCode][] = '/' . $folderImg . '/' . $img;
                                }
                            }
                        }
                    }
                }
            }

            $this->view->row_id_lang = $row_id_lang;
            $this->view->img_upload_home_lang_paths = $imgUploadArticleHomeLangPaths;
            $this->view->settingLangData = $settingLangData;
            $settingFormData = (object) $settingFormData;
        } else {
            $settingFormData = $setting;
        }

        $logoTextUp = "";
        $logoTextDown = "";
        if (!empty($setting->text_logo)) {
            $textLogo = json_decode($setting->text_logo, true);
            ;
            if (!empty($textLogo)) {
                $logoTextUp = $textLogo[0];
                if (count($textLogo) > 1) {
                    $logoTextDown = $textLogo[1];
                }
            }
        }

        $general = new General();
        $form = new SettingForm($settingFormData);
        $logo = $setting->logo;
        $favicon = $setting->favicon;
        $bgr_ycbg = $setting->bgr_ycbg;
        $image_meta = $setting->image_meta;
        $image_article_home = $setting->image_article_home;
        $banner_1 = $setting->banner_1;
        $banner_2 = $setting->banner_2;
        $banner_3 = $setting->banner_3;
        $banner_4 = $setting->banner_4;

        //article home
        $folderImg = 'uploads/' . $this->_get_subdomainFolder() . '/article_home/'. $setting->id;
        $dir = DOCUMENT_ROOT . '/public/' . $folderImg;

        $imgUploadArticleHomePaths = [];
        if (is_dir($dir)) {
            $imgUploads = array_filter(scandir($dir), function ($item) {
                return ($item[0] !== '.');
            });

            if (!empty($imgUploads)) {
                foreach ($imgUploads as $img) {
                    if ($img != 'medium') {
                        $imgUploadArticleHomePaths[] = '/' . $folderImg . '/' . $img;
                    }
                }
            }
        }

        // config item turn off phone alo
        $list_config_item = ConfigItem::find([
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND (field = '_turn_off_phone_alo' OR field = '_cf_radio_module_zalo_fb_hotline') AND active='Y'",
            "order" => "sort ASC, name ASC, id DESC"
        ]);

        $list_config_item_arr = [];
        if (count($list_config_item) > 0) {
            foreach ($list_config_item as $config_item) {
                $config_item_arr = [];
                if (isset($config_item->config_core->parent_id)) {
                    $config_item_arr = $config_item->toArray();
                    if ($config_item->type == 'radio' || $config_item->type == 'select' || $config_item->type == 'checkbox') {
                        $config_item_arr['list_value'] = json_decode($config_item->value, true);
                        if (!empty($config_item->config_core->guide)) {
                            $config_item_arr['guide'] = $config_item->config_core->guide;
                        }
                    }

                    $configCoreChilds= ConfigCore::find([
                        "conditions" => "parent_id = ". $config_item->config_core->id ." AND active='Y'",
                        "order" => "sort ASC, name ASC, id DESC"
                    ]);

                    if (count($configCoreChilds) > 0) {
                        $configItemChildArr = [];
                        foreach ($configCoreChilds as $key => $configCoreChild) {
                            $configItemChild = ConfigItem::findFirst([
                                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND config_core_id = $configCoreChild->id"
                            ]);
                            if ($configItemChild) {
                                $configItemChildArrChild = $configItemChild->toArray();
                                if ($configItemChild->type == 'radio' || $configItemChild->type == 'select' || $configItemChild->type == 'checkbox') {
                                    $configItemChildArrChild['list_value'] = json_decode($configItemChild->value, true);
                                    if (!empty($configItemChild->config_core->guide)) {
                                        $configItemChildArrChild['guide'] = $configItemChild->config_core->guide;
                                    }
                                }

                                $configItemChildArr[] = $configItemChildArrChild;
                            }
                        }

                        $config_item_arr['child'] = $configItemChildArr;
                    }
                }

                $list_config_item_arr[] = $config_item_arr;
            }
        }

        if ($this->request->isPost() && $form->isValid($this->request->getPost()) == true) {
            $request = $this->request->getPost();
            // save config
            if (!empty($list_config_item_arr)) {
                foreach ($list_config_item_arr as $row) {
                    $config_item = ConfigItem::findFirstById($row['id']);
                    if ($row['type'] == 'checkbox') {
                        $array_list_value = array();
                        foreach ($row['list_value'] as $key => $value) {
                            $array_list_value[] = $value;
                            if (isset($request[$row['field']])) {
                                if (in_array($value['name'], $request[$row['field']])) {
                                    $array_list_value[$key]['select'] = 1;
                                }
                                if (!in_array($value['name'], $request[$row['field']])) {
                                    $array_list_value[$key]['select'] = 0;
                                }
                            } else {
                                $array_list_value[$key]['select'] = 0;
                            }
                        }

                        $config_item->assign([
                            'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                        ]);
                        $config_item->save();
                    } else {
                        if (isset($request[$row['field']])) {
                            if ($row['type'] == 'text' || $row['type'] == 'email' || $row['type'] == 'textarea') {
                                $config_item->assign([
                                    'value' => $request[$row['field']]
                                ]);
                                $config_item->save();
                            }

                            if ($row['type'] == 'radio') {
                                $array_list_value = array();
                                foreach ($row['list_value'] as $key => $value) {
                                    $array_list_value[] = $value;
                                    if (in_array($value['name'], $request[$row['field']])) {
                                        $array_list_value[$key]['select'] = 1;
                                    }
                                    if (!in_array($value['name'], $request[$row['field']])) {
                                        $array_list_value[$key]['select'] = 0;
                                    }
                                }

                                $config_item->assign([
                                    'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                ]);
                                $config_item->save();
                            }

                            if ($row['type'] == 'select') {
                                $array_list_value = array();
                                foreach ($row['list_value'] as $key => $value) {
                                    $array_list_value[] = $value;
                                    if ($value['name'] == $request[$row['field']]) {
                                        $array_list_value[$key]['select'] = 1;
                                    } else {
                                        $array_list_value[$key]['select'] = 0;
                                    }
                                }

                                $config_item->assign([
                                    'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                ]);
                                $config_item->save();
                            }
                        }
                    }
                    
                    if (isset($row['child'])) {
                        $child = $row['child'];
                        foreach ($child as $ch) {
                            $config_item = ConfigItem::findFirstById($ch['id']);
                            $array_list_value = [];
                            if ($ch['type'] == 'checkbox') {
                                foreach ($ch['list_value'] as $key => $value) {
                                    $array_list_value[] = $value;
                                    if (isset($request[$ch['field']])) {
                                        if (in_array($value['name'], $request[$ch['field']])) {
                                            $array_list_value[$key]['select'] = 1;
                                        }
                                        if (!in_array($value['name'], $request[$ch['field']])) {
                                            $array_list_value[$key]['select'] = 0;
                                        }
                                    } else {
                                        $array_list_value[$key]['select'] = 0;
                                    }
                                }

                                $config_item->assign([
                                    'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                ]);
                                $config_item->save();
                            } else {
                                if (isset($request[$ch['field']])) {
                                    if ($ch['type'] == 'text' || $ch['type'] == 'email' || $ch['type'] == 'textarea') {
                                        $config_item->assign([
                                            'value' => $request[$ch['field']]
                                        ]);
                                        $config_item->save();

                                        if ($ch['field'] == '_txt_phone_alo' || $ch['field'] == '_cf_text_link_zalo' || $ch['field'] == '_cf_text_hotline_number') {
                                            // save hotline with redis key generate by day
                                            $hotlines = explode(";", $request[$ch['field']]);
                                            $hotlineKey = $ch['field'] . ':' . $this->_get_subdomainID();
                                            $this->redis_service->hmsetKeyHotlineByHours($hotlineKey, $dates, $hotlines);
                                        }
                                    }

                                    if ($ch['type'] == 'radio') {
                                        $array_list_value = array();
                                        foreach ($ch['list_value'] as $key => $value) {
                                            $array_list_value[] = $value;
                                            if (in_array($value['name'], $request[$ch['field']])) {
                                                $array_list_value[$key]['select'] = 1;
                                            }
                                            if (!in_array($value['name'], $request[$ch['field']])) {
                                                $array_list_value[$key]['select'] = 0;
                                            }
                                        }
                                        $config_item->assign([
                                            'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                        ]);
                                        $config_item->save();
                                    }
                                    if ($ch['type'] == 'select') {
                                        $array_list_value = array();
                                        foreach ($ch['list_value'] as $key => $value) {
                                            $array_list_value[] = $value;
                                            if ($value['name'] == $request[$ch['field']]) {
                                                $array_list_value[$key]['select'] = 1;
                                            } else {
                                                $array_list_value[$key]['select'] = 0;
                                            }
                                        }
                                        $config_item->assign([
                                            'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                        ]);
                                        $config_item->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            $text_logo = [];
            if ($this->request->getPost('logo_text_up') != '') {
                array_push($text_logo, $this->request->getPost('logo_text_up'));
            }
            if ($this->request->getPost('logo_text_down') != '') {
                array_push($text_logo, $this->request->getPost('logo_text_down'));
            }
            $text_logo = json_encode($text_logo, JSON_UNESCAPED_UNICODE);
            $folder = $this->_get_subdomainFolder();

            $data = [
                'row_id' => $setting->id,
                'name' => $this->request->getPost('name'),
                'address' => $this->request->getPost('address'),
                'email' => $this->request->getPost('email'),
                // 'email_order' => $this->request->getPost('email_order'),
                // 'phone' => $this->request->getPost('phone'),
                'website' => $this->request->getPost('website'),
                'slogan' => $this->request->getPost('slogan'),
                'hotline' => $this->request->getPost('hotline'),
                // 'fax' => $this->request->getPost('fax'),
                'business_license' => $this->request->getPost('business_license'),
                'tax_code' => $this->request->getPost('tax_code'),
                'copyright' => $this->request->getPost('copyright'),
                'facebook' => $this->request->getPost('facebook'),
                'title' => $this->request->getPost('title'),
                'keywords' => $this->request->getPost('keywords'),
                'description' => $this->request->getPost('description'),
                'note_payment_method_2' => $this->request->getPost('note_payment_method_2'),
                // 'analytics' => $this->request->getPost('analytics'),
                'head_content' => $this->request->getPost('head_content'),
                'body_content' => $this->request->getPost('body_content'),
                'facebook' => $this->request->getPost('facebook'),
                'google' => $this->request->getPost('google'),
                'twitter' => $this->request->getPost('twitter'),
                'youtube' => $this->request->getPost('youtube'),
                'article_home' => $this->request->getPost('article_home'),
                'contact' => $this->request->getPost('contact'),
                'footer' => $this->request->getPost('footer'),
                'enable_logo_text' => $this->request->getPost('enable_logo_text'),
                'text_logo' => $text_logo,
                'enable_contact_default' => $this->request->getPost('enable_contact_default'),
                'enable_footer_default' => $this->request->getPost('enable_footer_default'),
                'enable_form_contact' => $this->request->getPost('enable_form_contact'),
                'enable_form_reg_article_home' => $this->request->getPost('enable_form_reg_article_home'),
                'enable_video_article_home' => $this->request->getPost('enable_video_article_home'),
                'enable_image_article_home' => $this->request->getPost('enable_image_article_home'),
                'enable_search_advance_article_home' => $this->request->getPost('enable_search_advance_article_home'),
                'enable_image_menu_2' => $this->request->getPost('enable_image_menu_2'),
                'youtube_code' => $this->request->getPost('youtube_code'),
                'enable_map' => $this->request->getPost('enable_map'),
                'map_code' => $this->request->getPost('map_code'),
                'order_admin_note' => $this->request->getPost('order_admin_note'),
            ];

            $subFolder = $this->_get_subdomainFolder();
            $subfolderUrl = 'files/default/' . $subFolder;
            if ($this->request->hasFiles() == true) {
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    if (!empty($file->getName())) {
                        $fileName = basename($file->getName(), "." . $file->getExtension());
                        $fileName = $general->create_slug($fileName);
                        $subCode = Text::random(Text::RANDOM_ALNUM);
                        $fileFullName = $fileName . '_' . $subCode . '.' . $file->getExtension();
                        
                        // upload logo
                        if ($file->getKey() == 'logo') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                            if (!empty($dataUpload['file_name'])) {
                                $data['logo'] = $dataUpload['file_name'];
                                if (!empty($setting->logo) && file_exists($subfolderUrl . '/' . $setting->logo)) {
                                    unlink($subfolderUrl . '/' . $setting->logo);
                                }
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }

                        // upload favicon
                        if ($file->getKey() == 'favicon') {
                            
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                            if (!empty($dataUpload['file_name'])) {
                                $data['favicon'] = $dataUpload['file_name'];
                                if (!empty($setting->favicon) && file_exists($subfolderUrl . '/' . $setting->favicon)) {
                                    unlink($subfolderUrl . '/' . $setting->favicon);
                                }
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }

                        // upload background ycbg
                        if ($file->getKey() == 'bgr_ycbg') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                            if (!empty($dataUpload['file_name'])) {
                                $data['bgr_ycbg'] = $dataUpload['file_name'];
                                if (!empty($setting->bgr_ycbg) && file_exists($subfolderUrl . '/' . $setting->bgr_ycbg)) {
                                    unlink($subfolderUrl . '/' . $setting->bgr_ycbg);
                                }
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }

                        // upload image meta share facebook
                        if ($file->getKey() == 'image_meta') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                            if (!empty($dataUpload['file_name'])) {
                                $data['image_meta'] = $dataUpload['file_name'];
                                if (!empty($setting->image_meta) && file_exists($subfolderUrl . '/' . $setting->image_meta)) {
                                    unlink($subfolderUrl . '/' . $setting->image_meta);
                                }
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }

                        // upload image for module menu 2
                        if ($file->getKey() == 'image_menu_2') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                            if (!empty($dataUpload['file_name'])) {
                                $data['image_menu_2'] = $dataUpload['file_name'];
                                if (!empty($setting->image_menu_2) && file_exists($subfolderUrl . '/' . $setting->image_menu_2)) {
                                    unlink($subfolderUrl . '/' . $setting->image_menu_2);
                                }
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }

                        // upload image for module article home
                        if ($file->getKey() == 'image_article_home') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                            if (!empty($dataUpload['file_name'])) {
                                $data['image_article_home'] = $dataUpload['file_name'];
                                if (!empty($setting->image_article_home) && file_exists($subfolderUrl . '/' . $setting->image_article_home)) {
                                    unlink($subfolderUrl . '/' . $setting->image_article_home);
                                }
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }
                    }
                }
            }
            
            $setting->assign($data);
            $setting->save();

            //save other language
            if (count($this->_tmpSubdomainLanguages) > 0) {
                foreach ($this->_tmpSubdomainLanguages as $tmp) {
                    $data = [];
                    $langId = $tmp->language_id;
                    $langCode = $tmp->language->code;
                    if ($langCode != 'vi') {
                        $text_logo = '';
                        if ($this->request->getPost('logo_text_up_' . $langCode) != '') {
                            array_push($text_logo, $this->request->getPost('logo_text_up_' . $langCode));
                        }

                        if ($this->request->getPost('logo_text_down_' . $langCode) != '') {
                            array_push($text_logo, $this->request->getPost('logo_text_down_' . $langCode));
                        }

                        $text_logo = ($text_logo != '') ? json_encode($text_logo, JSON_UNESCAPED_UNICODE) : $setting->text_logo;

                        $dataLang = $setting->toArray();
                        $dataLang['language_id'] = $langId;
                        $dataLang['depend_id'] = $setting->id;
                        $dataLang['row_id'] = $$row_id_lang[$langCode];
                        $dataLang['name'] = $this->request->getPost('name_' . $langCode);
                        $dataLang['address'] = $this->request->getPost('address_' . $langCode);
                        $dataLang['slogan'] = $this->request->getPost('slogan_' . $langCode);
                        $dataLang['business_license'] = $this->request->getPost('business_license_' . $langCode);
                        $dataLang['copyright'] = $this->request->getPost('copyright_' . $langCode);
                        $dataLang['title'] = $this->request->getPost('title_' . $langCode);
                        $dataLang['keywords'] = $this->request->getPost('keywords_' . $langCode);
                        $dataLang['description'] = $this->request->getPost('description_' . $langCode);
                        $dataLang['note_payment_method_2'] = $this->request->getPost('note_payment_method_2_' . $langCode);
                        $dataLang['article_home'] = $this->request->getPost('article_home_' . $langCode);
                        $dataLang['contact'] = $this->request->getPost('contact_' . $langCode);
                        $dataLang['footer'] = $this->request->getPost('footer_' . $langCode);
                        $dataLang['enable_logo_text'] = $this->request->getPost('enable_logo_text_' . $langCode);
                        $dataLang['text_logo'] = $text_logo;
                        

                        if ($this->request->hasFiles() == true) {
                            $subFolder = $this->_get_subdomainFolder();
                            $files = $this->request->getUploadedFiles();
                            foreach ($files as $file) {
                                if (!empty($file->getName())) {
                                    // upload logo lang
                                    if ($file->getKey() == 'logo_' . $langCode) {
                                        $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                                        if (!empty($dataUpload['file_name'])) {
                                            $dataLang['logo'] = $dataUpload['file_name'];
                                            if (!empty($setting->logo . '_' . $langCode) && file_exists($subfolderUrl . '/' . $setting->logo . '_' . $langCode)) {
                                                unlink($subfolderUrl . '/' . $setting->logo . '_' . $langCode);
                                            }
                                        } else {
                                            $this->flashSession->error( $dataUpload['message']);
                                            return $this->response->redirect($this->router->getRewriteUri());
                                        }
                                    }
                                }
                            }
                        }

                        $settingLang = Setting::findFirst(array(
                            'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .''
                        ));
                        if (!$settingLang) {
                            $settingLang = new Setting();
                        }

                        unset($dataLang['id']);
                        $settingLang->assign($dataLang);
                        if (!$settingLang->save()) {
                            foreach ($settingLang->getMessages() as $message) {
                                echo $message;
                            }
                        } else {
                            $settingLang = Setting::findFirstById($settingLang->id);
                            $settingLang->youtube_code = $setting->youtube_code;
                            $settingLang->map_code = $setting->map_code;
                            $settingLang->save();
                        }
                    }
                }
            }

            foreach ($this->_tmpSubdomainLanguages as $tmp) {
                $langCode = $tmp->language->code;
                $this->cookies->get('row_id_setting_' . $langCode . '_' . $this->_get_subdomainID())->delete();
            }

            // save hotline with redis key generate by day
            $hotlines = explode(";", $setting->hotline);
            $hotlineKey = 'hotline:' . $this->_get_subdomainID();
            $this->redis_service->hmsetKeyHotlineByHours($hotlineKey, $dates, $hotlines);

            $this->flashSession->success($this->_message["edit"]);

            // Make a full HTTP redirection
            // return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/content?active=' . $this->request->getPost('active'));
            return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/content');
        }

        $breadcrumb = '<li class="active">Cài đặt nội dung</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->setting = $setting;
        $this->view->logo_text_up = $logoTextUp;
        $this->view->logo_text_down = $logoTextDown;
        $this->view->list_config_item_arr = $list_config_item_arr;
        /*$this->view->banner_html_sub = $bannerHtmlSub;
        $this->view->banner_html = $bannerHtml;*/
        $this->view->folder = $this->_get_subdomainFolder();
        $this->view->form = $form;
        $this->view->img_upload_home_paths = $imgUploadArticleHomePaths;
    }

    /**
     * Set config for web
     * 
     * @return View
     */
    public function configAction()
    {
        $list_config_item = ConfigItem::find([
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND (field != '_cf_radio_menu_google_translate' AND field != '_cf_radio_menu_language_database' AND field != '_turn_off_phone_alo' AND field != '_cf_radio_module_zalo_fb_hotline') AND active='Y'",
            "order" => "sort ASC, name ASC, id DESC"
        ]);

        $list_config_item_arr = array();
        $list_config_item_child_arr = array();
        if (count($list_config_item) > 0) {
            $i = 0;
            $j = 0;
            foreach ($list_config_item as $config_item) {
                if (isset($config_item->config_core->parent_id)) {
                    if ($config_item->config_core->parent_id == 0) {
                        $list_config_item_arr[] = $config_item->toArray();
                        if ($config_item->type == 'radio' || $config_item->type == 'select' || $config_item->type == 'checkbox') {
                            $list_config_item_arr[$i]['list_value'] = json_decode($config_item->value, true);
                            if (!empty($config_item->config_core->guide)) {
                                $list_config_item_arr[$i]['guide'] = $config_item->config_core->guide;
                            }
                        }

                        $i++;
                    } else {
                        $list_config_item_child_arr[$config_item->config_core->parent_id][$j] = $config_item->toArray();
                        $list_config_item_child_arr[$config_item->config_core->parent_id][$j]['guide'] = $config_item->config_core->guide;
                    }
                }

                $j++;
            }
        }

        foreach ($list_config_item_arr as $key => $config_item) {
            if (isset($list_config_item_child_arr[$config_item['config_core_id']])) {
                foreach ($list_config_item_child_arr[$config_item['config_core_id']] as $k => $v) {
                    if ($v['type'] == 'radio' || $v['type'] == 'select' || $v['type'] == 'checkbox') {
                        $list_config_item_child_arr[$config_item['config_core_id']][$k]['list_value'] = json_decode($v['value'], true);
                    }
                }

                $list_config_item_arr[$key]['child'] = $list_config_item_child_arr[$config_item['config_core_id']];
                $list_config_item_arr[$key]['child'] = array_values($list_config_item_arr[$key]['child']);
            }
        }

        // get modules load in product detail page
        $modules = [];
        $moduleItems = $this->modelsManager->createBuilder()
            ->columns(
                "mi.module_group_id,
                mi.parent_id,
                mi.name AS module_name,
                mi.id AS module_id,
                mi.module_group_id,
                mi.sort AS module_sort,
                mi.type AS module_type,
                tmp.id,
                tmp.type,
                tmp.active,
                tmp.sort"
            )
            ->addFrom("Modules\Models\ModuleItem", "mi")
            ->leftJoin("Modules\Models\TmpTypeModule", "mi.id = tmp.module_item_id", "tmp")
            ->where("mi.subdomain_id = ". $this->_get_subdomainID() ." AND parent_id = 0")
            ->orderBy("tmp.sort ASC, tmp.id DESC, mi.name ASC, mi.sort ASC, mi.type ASC, mi.id DESC")
            ->getQuery()
            ->execute();

        foreach ($moduleItems as $key => $moduleItem) {
            switch ($moduleItem->module_type) {
                case 'banner':
                    $bannerType = BannerType::findFirstByModuleItemId($moduleItem->module_id);
                    $url = ACP_NAME . '/banner';
                    break;
                case 'post':
                    $post = Posts::findFirstByModuleItemId($moduleItem->module_id);
                    $url = ACP_NAME . '/posts';
                    break;
                case 'menu':
                    $menu = Menu::findFirstByModuleItemId($moduleItem->module_id);
                    if ($menu) {
                        $url = ACP_NAME . '/menu/update/' . $menu->id;
                    }
                    break;
                
                default:
                    $moduleGroup = ModuleGroup::findFirstById($moduleItem->module_group_id);
                    if ($moduleGroup && !empty($moduleGroup->link) != '') {
                        $url = ACP_NAME . '/' . $moduleGroup->link;
                    } else {
                        $url = '';
                    }
                      
                    break;
            }

            $itemModule = $moduleItem->toArray();
            $itemModule['url'] = $url;
            $itemModule['module_name'] = ($moduleItem->module_type == 'post') ? 'Tự soạn thảo: ' . $moduleItem->module_name : $moduleItem->module_name;

            // get child
            if ($moduleItem->module_type != 'banner' && $moduleItem->module_type != 'post' && $moduleItem->module_type != 'menu') {
                $moduleItemChilds = $this->modelsManager->createBuilder()
                    ->columns(
                        "mi.module_group_id,
                        mi.parent_id,
                        mi.name AS module_name,
                        mi.id AS module_id,
                        mi.module_group_id,
                        mi.sort AS module_sort,
                        mi.type AS module_type,
                        mi.active AS module_active,
                        tmp.id,
                        tmp.type,
                        tmp.active,
                        tmp.sort"
                    )
                    ->addFrom("Modules\Models\ModuleItem", "mi")
                    ->leftJoin("Modules\Models\TmpTypeModule", "mi.id = tmp.module_item_id", "tmp")
                    ->where("mi.subdomain_id = ". $this->_get_subdomainID() ." AND parent_id = ". $moduleItem->module_id ."")
                    ->orderBy("tmp.sort ASC, tmp.id DESC, mi.sort ASC, mi.name ASC, mi.type ASC, mi.id DESC")
                    ->getQuery()
                    ->execute();

                if (count($moduleItemChilds) == 0) {
                    $moduleItemChilds = ModuleItem::find([
                        'columns' => 'id AS module_id, parent_id, name AS module_name, level, type, sort, active',
                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND parent_id = '. $moduleItem->module_id .''
                    ]);
                }

                if (count($moduleItemChilds) > 0) {
                    $itemModule['child'] = $moduleItemChilds->toArray();
                }
            }
           
            $modules[] = $itemModule;
        }

        uasort($modules, function ($a, $b) {
            if ($a['sort'] == "") {
                return 1;
            }
            if ($b['sort'] == "") {
                return -1;
            }
            return $a['sort'] - $b['sort'];
        });

        $modules = array_values($modules);

        if ($this->request->isPost()) {
            $request = $this->request->getPost();
            if (!empty($list_config_item_arr)) {
                foreach ($list_config_item_arr as $row) {
                    $config_item = ConfigItem::findFirstById($row['id']);
                    if ($row['type'] == 'checkbox') {
                        $array_list_value = array();
                        foreach ($row['list_value'] as $key => $value) {
                            $array_list_value[] = $value;
                            if (isset($request[$row['field']])) {
                                if (in_array($value['name'], $request[$row['field']])) {
                                    $array_list_value[$key]['select'] = 1;
                                }
                                if (!in_array($value['name'], $request[$row['field']])) {
                                    $array_list_value[$key]['select'] = 0;
                                }
                            } else {
                                $array_list_value[$key]['select'] = 0;
                            }
                        }

                        $config_item->assign([
                            'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                        ]);
                        $config_item->save();
                    } else {
                        if (isset($request[$row['field']])) {
                            if ($row['type'] == 'text' || $row['type'] == 'email' || $row['type'] == 'textarea') {
                                $config_item->assign([
                                    'value' => $request[$row['field']]
                                ]);
                                $config_item->save();
                            }

                            if ($row['type'] == 'radio') {
                                $array_list_value = array();
                                foreach ($row['list_value'] as $key => $value) {
                                    $array_list_value[] = $value;
                                    if (in_array($value['name'], $request[$row['field']])) {
                                        $array_list_value[$key]['select'] = 1;
                                    }
                                    if (!in_array($value['name'], $request[$row['field']])) {
                                        $array_list_value[$key]['select'] = 0;
                                    }
                                }

                                $config_item->assign([
                                    'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                ]);
                                $config_item->save();
                            }

                            if ($row['type'] == 'select') {
                                $array_list_value = array();
                                foreach ($row['list_value'] as $key => $value) {
                                    $array_list_value[] = $value;
                                    if ($value['name'] == $request[$row['field']]) {
                                        $array_list_value[$key]['select'] = 1;
                                    } else {
                                        $array_list_value[$key]['select'] = 0;
                                    }
                                }

                                $config_item->assign([
                                    'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                ]);
                                $config_item->save();
                            }
                        }
                    }
                    
                    if (isset($row['child'])) {
                        $child = $row['child'];
                        foreach ($child as $ch) {
                            $config_item = ConfigItem::findFirstById($ch['id']);
                            $array_list_value = [];
                            if ($ch['type'] == 'checkbox') {
                                foreach ($ch['list_value'] as $key => $value) {
                                    $array_list_value[] = $value;
                                    if (isset($request[$ch['field']])) {
                                        if (in_array($value['name'], $request[$ch['field']])) {
                                            $array_list_value[$key]['select'] = 1;
                                        }
                                        if (!in_array($value['name'], $request[$ch['field']])) {
                                            $array_list_value[$key]['select'] = 0;
                                        }
                                    } else {
                                        $array_list_value[$key]['select'] = 0;
                                    }
                                }

                                $config_item->assign([
                                    'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                ]);
                                $config_item->save();
                            } else {
                                if (isset($request[$ch['field']])) {
                                    if ($ch['type'] == 'text' || $ch['type'] == 'email' || $ch['type'] == 'textarea') {
                                        $config_item->assign([
                                            'value' => $request[$ch['field']]
                                        ]);
                                        $config_item->save();
                                    }
                                    if ($ch['type'] == 'radio') {
                                        $array_list_value = array();
                                        foreach ($ch['list_value'] as $key => $value) {
                                            $array_list_value[] = $value;
                                            if (in_array($value['name'], $request[$ch['field']])) {
                                                $array_list_value[$key]['select'] = 1;
                                            }
                                            if (!in_array($value['name'], $request[$ch['field']])) {
                                                $array_list_value[$key]['select'] = 0;
                                            }
                                        }
                                        $config_item->assign([
                                            'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                        ]);
                                        $config_item->save();
                                    }
                                    if ($ch['type'] == 'select') {
                                        $array_list_value = array();
                                        foreach ($ch['list_value'] as $key => $value) {
                                            $array_list_value[] = $value;
                                            if ($value['name'] == $request[$ch['field']]) {
                                                $array_list_value[$key]['select'] = 1;
                                            } else {
                                                $array_list_value[$key]['select'] = 0;
                                            }
                                        }
                                        $config_item->assign([
                                            'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                        ]);
                                        $config_item->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // add module product
            TmpTypeModule::deleteByRawSql("subdomain_id = ". $this->_get_subdomainID() ." AND type = 'product'");
            if ($this->request->getPost('active_module')) {
                $activeModules = $this->request->getPost('active_module');
                $sortModules = $this->request->getPost('sort_module');
                foreach ($activeModules as $key => $activeModule) {
                    $moduleItem = ModuleItem::findFirstById($key);
                    if ($moduleItem->parent_id == 0) {
                        $tmpTypeModule = new TmpTypeModule();
                        $tmpTypeModule->assign([
                            'subdomain_id' => $this->_get_subdomainID(),
                            'module_item_id' => $key,
                            'type' => 'product',
                            'active' => 'Y',
                            'sort' => $sortModules[$key],
                        ]);

                        $tmpTypeModule->save();
                    } else {
                        $moduleItemParentId = $moduleItem->parent_id;
                        if (isset($activeModules[$moduleItemParentId])) {
                            $tmpTypeModule = new TmpTypeModule();
                            $tmpTypeModule->assign([
                                'subdomain_id' => $this->_get_subdomainID(),
                                'module_item_id' => $key,
                                'type' => 'product',
                                'active' => 'Y',
                                'sort' => $sortModules[$key],
                            ]);

                            $tmpTypeModule->save();
                        }
                    }
                }
            }

            // add module category
            TmpTypeModule::deleteByRawSql("subdomain_id = ". $this->_get_subdomainID() ." AND type = 'category'");
            if ($this->request->getPost('active_module_category')) {
                $activeModules = $this->request->getPost('active_module_category');
                $sortModules = $this->request->getPost('sort_module_category');
                foreach ($activeModules as $key => $activeModule) {
                    $moduleItem = ModuleItem::findFirstById($key);
                    if ($moduleItem->parent_id == 0) {
                        $tmpTypeModule = new TmpTypeModule();
                        $tmpTypeModule->assign([
                            'subdomain_id' => $this->_get_subdomainID(),
                            'module_item_id' => $key,
                            'type' => 'category',
                            'active' => 'Y',
                            'sort' => $sortModules[$key],
                        ]);

                        $tmpTypeModule->save();
                    } else {
                        $moduleItemParentId = $moduleItem->parent_id;
                        if (isset($activeModules[$moduleItemParentId])) {
                            $tmpTypeModule = new TmpTypeModule();
                            $tmpTypeModule->assign([
                                'subdomain_id' => $this->_get_subdomainID(),
                                'module_item_id' => $key,
                                'type' => 'category',
                                'active' => 'Y',
                                'sort' => $sortModules[$key],
                            ]);

                            $tmpTypeModule->save();
                        }
                    }
                }
            }

            // add module news_menu
            TmpTypeModule::deleteByRawSql("subdomain_id = ". $this->_get_subdomainID() ." AND type = 'news_menu'");
            if ($this->request->getPost('active_module_news_menu')) {
                $activeModules = $this->request->getPost('active_module_news_menu');
                $sortModules = $this->request->getPost('sort_module_news_menu');
                foreach ($activeModules as $key => $activeModule) {
                    $moduleItem = ModuleItem::findFirstById($key);
                    if ($moduleItem->parent_id == 0) {
                        $tmpTypeModule = new TmpTypeModule();
                        $tmpTypeModule->assign([
                            'subdomain_id' => $this->_get_subdomainID(),
                            'module_item_id' => $key,
                            'type' => 'news_menu',
                            'active' => 'Y',
                            'sort' => $sortModules[$key],
                        ]);

                        $tmpTypeModule->save();
                    } else {
                        $moduleItemParentId = $moduleItem->parent_id;
                        if (isset($activeModules[$moduleItemParentId])) {
                            $tmpTypeModule = new TmpTypeModule();
                            $tmpTypeModule->assign([
                                'subdomain_id' => $this->_get_subdomainID(),
                                'module_item_id' => $key,
                                'type' => 'news_menu',
                                'active' => 'Y',
                                'sort' => $sortModules[$key],
                            ]);

                            $tmpTypeModule->save();
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["edit"]);

            // Make a full HTTP redirection
            return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/config');
        }

        $breadcrumb = '<li class="active">Cấu hình website/li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->modules = $modules;
        $this->view->list_config_item_arr = $list_config_item_arr;
    }

    /**
     * Select module display for web
     * 
     * @return View
     */
    public function newinterfaceAction()
    {
        $identity = $this->auth->getIdentity();
        $setting = Setting::findFirst(array(
            'conditions' => 'Modules\Models\Setting.subdomain_id = '. $this->_get_subdomainID() .''
        ));

        $layoutId = $setting->layout_id;

        $layout = Layout::find(
            array(
                "order" => "sort ASC, id DESC",
            )
        );

        $layoutConfig = LayoutConfig::findFirst([
            "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND layout_id = $layoutId"
        ]);

        $css_item = ($layoutConfig && !empty($layoutConfig->css)) ? json_decode($layoutConfig->css) : "";

        $background = Background::findFirst([
            "conditions" => "layout_config_id = $layoutConfig->id"
        ]);

        $tmpModuleGroupLayout = TmpModuleGroupLayout::find();
        $arrayTmp = [];
        if (count($tmpModuleGroupLayout) > 0) {
            foreach ($tmpModuleGroupLayout as $row) {
                $arrayTmp[$row->layout_id][0] = 0;
                $arrayTmp[$row->layout_id][] = $row->module_group_id;
            }
        }

        $general = new General();
        $form = new SettingForm($setting);

        if ($this->request->isPost()) {
            $request = $this->request->getPost();

            //sort header
            $positions = Position::find(["conditions" => "active = 'Y' AND deleted = 'N'"]);
            TmpLayoutModule::deleteByRawSql('subdomain_id ='. $this->_get_subdomainID() .'');
            foreach ($positions as $position) {
                // active
                if ($this->request->getPost('active_module_' . $position->code. '_' . $layoutId)) {
                    $activeModules = $this->request->getPost('active_module_' . $position->code. '_' . $layoutId);
                    $sortModules = $this->request->getPost('sort_module_' . $position->code. '_' . $layoutId);

                    foreach ($activeModules as $key => $activeModule) {
                        $moduleItem = ModuleItem::findFirstById($key);
                        if ($moduleItem->parent_id == 0) {
                            $tmpLayoutModule = new TmpLayoutModule();
                            $tmpLayoutModule->assign([
                                'subdomain_id' => $this->_get_subdomainID(),
                                'layout_id' => $layoutId,
                                'position_id' => $position->id,
                                'module_item_id' => $key,
                                'active' => 'Y',
                                'sort' => $sortModules[$key],
                            ]);

                            $tmpLayoutModule->save();
                        } else {
                            $moduleItemParentId = $moduleItem->parent_id;
                            if (isset($activeModules[$moduleItemParentId])) {
                                $tmpLayoutModule = new TmpLayoutModule();
                                $tmpLayoutModule->assign([
                                    'subdomain_id' => $this->_get_subdomainID(),
                                    'layout_id' => $layoutId,
                                    'position_id' => $position->id,
                                    'module_item_id' => $key,
                                    'active' => 'Y',
                                    'sort' => $sortModules[$key],
                                ]);

                                $tmpLayoutModule->save();
                            }
                        }
                    }
                }
            }

            // save layout config
            $layoutConfigPost = LayoutConfig::findFirst([
                "conditions" => "subdomain_id = ".$this->_get_subdomainID()." AND layout_id = $layoutId"
            ]);

            $data_bgr = array();
            if (empty($background)) {
                $background = new Background();
                $data_bgr['subdomain_id'] = $this->_get_subdomainID();
                $data_bgr['layout_config_id'] = $layoutConfigPost->id;
                $data_bgr['sort'] = 1;
            } else {
                $photo = $background->photo;
                if ($this->request->getPost('delete_background')) {
                    $data['css']["background_photo"] = '';
                    $data_bgr['photo'] = '';
                    @unlink("files/default/" . $this->_get_subdomainFolder() . "/" . $photo);
                } else {
                    $data['css']["background_photo"] = $photo;
                    if (file_exists(('files/default/' . $this->_get_subdomainFolder() . '/' . $photo))) {
                        $dataCss["background_photo"] = $photo;
                    }
                }
            }

            $color = $this->request->getPost('color');

            if (!empty($color)) {
                $data_bgr['color'] = $color;
                $dataCss["background_color"] = $color;
            }
            $data_bgr['text_color'] = $this->request->getPost('text_color');

            $data_bgr['type'] = $this->request->getPost('type');
            $data_bgr['attachment'] = $this->request->getPost('attachment');
            $data_bgr['active'] = $this->request->getPost('bgr_active');

            $dataCss["background_type"] = $this->request->getPost('type');
            $dataCss["background_attachment"] = $this->request->getPost('attachment');
            $dataCss["background_active"] = $this->request->getPost('bgr_active');

            //set css layout
            $dataCss['font_web'] = $this->request->getPost('font_web');
            $dataCss['container'] = $this->request->getPost('container');
            $dataCss['bar_web_bgr'] = $this->request->getPost('bar_web_bgr');
            $dataCss['bar_web_color'] = $this->request->getPost('bar_web_color');
            $dataCss['txt_web_color'] = $this->request->getPost('txt_web_color');
            $dataCss['menu_top_color'] = $this->request->getPost('menu_top_color');

            $enableColor = $this->request->getPost("enable_color");
            $subFolder = $this->_get_subdomainFolder();
            $subfolderUrl = 'files/default/' . $subFolder;
            if ($this->request->hasFiles() == true) {
                $subFolder = $this->_get_subdomainFolder();
                $files = $this->request->getUploadedFiles();
                foreach ($files as $file) {
                    if (!empty($file->getName())) {
                        // upload background for web
                        if ($file->getKey() == 'photo') {
                            $dataUpload = $this->upload_service->upload($file, $subfolderUrl);
                            if (!empty($dataUpload['file_name'])) {
                                $data_bgr['photo'] = $dataUpload['file_name'];
                                $dataCss["background_photo"] = $dataUpload['file_name'];
                                if (isset($photo)) {
                                    unlink($subfolderUrl . '/' . $photo);
                                }
                            } else {
                                $this->flashSession->error( $dataUpload['message']);
                                return $this->response->redirect($this->router->getRewriteUri());
                            }
                        }
                    } elseif ($this->request->getPost('brg_select') != '') {
                        copy('assets/source/background/' . $this->request->getPost('brg_select'), 'files/default/' . $this->_get_subdomainFolder() . '/' . $this->request->getPost('brg_select'));
                        $data_bgr['photo'] = $this->request->getPost('brg_select');
                        $dataCss["background_photo"] = $this->request->getPost('brg_select');
                        @unlink('files/default/' . $this->_get_subdomainFolder() . '/' . $photo);
                    }
                }
            }

            $background->assign($data_bgr);
            $background->save();

            $css = json_encode($dataCss);

            $pageCss = $this->getCustomCss($dataCss, $enableColor);

            $file = "assets/css/pages/". $this->_get_subdomainFolder() ."/page" . $layoutId . ".css";
            $minifyFile = "assets/css/pages/". $this->_get_subdomainFolder() ."/page.min.css";

            if ($this->request->getPost("enable_css") == 1) {
                file_put_contents($file, "");
                file_put_contents($file, $pageCss, FILE_APPEND | LOCK_EX);

                // save minified file to disk
                $minifier = new Minify\CSS($file);
                $minifier->minify($minifyFile);
            }

            $layoutConfigPost->assign([
                'hide_header' => $this->request->getPost('hide_header'),
                'hide_left' => $this->request->getPost('hide_left'),
                'hide_right' => $this->request->getPost('hide_right'),
                'hide_footer' => $this->request->getPost('hide_footer'),
                'show_left_inner' => $this->request->getPost('show_left_inner'),
                'show_right_inner' => $this->request->getPost('show_right_inner'),
                'css' => $css,
                'enable_css' => $this->request->getPost("enable_css"),
                'enable_color' => $this->request->getPost("enable_color"),
            ]);
            $layoutConfigPost->save();

            
            $this->flashSession->success($this->_message["edit"]);

            $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/' . $this->_getActionName());
        }

        $position = Position::find([
            'columns' => 'id, name, code',
            "conditions" => "active = 'Y'",
            'order' => 'sort ASC, id DESC'
        ]);

        $moduleItemAlls = ModuleItem::find([
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND parent_id = 0"
        ]);

        $moduleItems = $this->modelsManager->createBuilder()
            ->columns(
                "mi.module_group_id,
                mi.parent_id,
                mi.name AS module_name,
                mi.id AS module_id,
                mi.module_group_id,
                mi.sort AS module_sort,
                mi.type AS module_type,
                tmp.id,
                tmp.layout_id,
                tmp.position_id,
                tmp.active,
                tmp.sort"
            )
            ->addFrom("Modules\Models\ModuleItem", "mi")
            ->leftJoin("Modules\Models\TmpLayoutModule", "mi.id = tmp.module_item_id", "tmp")
            ->where("mi.subdomain_id = ". $this->_get_subdomainID() ." AND parent_id = 0")
            ->inWhere("mi.module_group_id", $arrayTmp[$layoutId])
            ->orderBy("tmp.sort ASC, tmp.id DESC, mi.name ASC, mi.sort ASC, mi.id DESC")
            ->getQuery()
            ->execute();

        $position_module_array = [];
        $moduleArray = [
            'header' => [],
            'left' => [],
            'right' => [],
            'center' => [],
            'footer' => [],
        ];

       
        $existTmpArray = [];
        $unsetArray = [];
        foreach ($position as $p) {
            foreach ($moduleItems as $key => $moduleItem) {
                switch ($moduleItem->module_type) {
                    case 'banner':
                        $bannerType = BannerType::findFirstByModuleItemId($moduleItem->module_id);
                        $url = ACP_NAME . '/banner';
                        break;
                    case 'post':
                        $post = Posts::findFirstByModuleItemId($moduleItem->module_id);
                        $url = ACP_NAME . '/posts';
                        break;
                    case 'menu':
                        $menu = Menu::findFirstByModuleItemId($moduleItem->module_id);
                        if ($menu) {
                            $url = ACP_NAME . '/menu/update/' . $menu->id;
                        }
                        break;
                    
                    default:
                        $moduleGroup = ModuleGroup::findFirstById($moduleItem->module_group_id);
                        if ($moduleGroup && !empty($moduleGroup->link) != '') {
                            $url = ACP_NAME . '/' . $moduleGroup->link;
                        } else {
                            $url = '';
                        }
                          
                        break;
                }

                $item = $moduleItem->toArray();
                $item['url'] = $url;
                $item['module_name'] = ($moduleItem->module_type == 'post') ? 'Tự soạn thảo: ' . $moduleItem->module_name : $moduleItem->module_name;

                // get child
                if ($moduleItem->module_type != 'banner' && $moduleItem->module_type != 'post' && $moduleItem->module_type != 'menu') {
                    $moduleItemChilds = $this->modelsManager->createBuilder()
                        ->columns(
                            "mi.module_group_id,
                            mi.parent_id,
                            mi.name AS module_name,
                            mi.id AS module_id,
                            mi.module_group_id,
                            mi.sort AS module_sort,
                            mi.type AS module_type,
                            mi.active AS module_active,
                            tmp.id,
                            tmp.layout_id,
                            tmp.position_id,
                            tmp.active,
                            tmp.sort"
                        )
                        ->addFrom("Modules\Models\ModuleItem", "mi")
                        ->leftJoin("Modules\Models\TmpLayoutModule", "mi.id = tmp.module_item_id", "tmp")
                        ->where("mi.subdomain_id = ". $this->_get_subdomainID() ." AND parent_id = ". $moduleItem->module_id ."")
                        ->orderBy("tmp.sort ASC, tmp.id DESC, mi.sort ASC, mi.name ASC, mi.id DESC")
                        ->getQuery()
                        ->execute();

                    if (count($moduleItemChilds) == 0) {
                        $moduleItemChilds = ModuleItem::find([
                            'columns' => 'id AS module_id, parent_id, name AS module_name, level, type, sort, active',
                            'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND parent_id = '. $moduleItem->module_id .''
                        ]);
                    }

                    if (count($moduleItemChilds) > 0) {
                        $itemChilds = [];
                        foreach ($moduleItemChilds as $moduleItemChild) {
                            switch ($moduleItemChild->module_type) {
                                case 'banner':
                                    $bannerType = BannerType::findFirstByModuleItemId($moduleItemChild->module_id);
                                    $urlChild = ACP_NAME . '/banner';
                                    break;
                                case 'post':
                                    $post = Posts::findFirstByModuleItemId($moduleItemChild->module_id);
                                    $urlChild = ACP_NAME . '/posts';
                                    break;
                                case 'menu':
                                    $menu = Menu::findFirstByModuleItemId($moduleItemChild->module_id);
                                    if ($menu) {
                                        $urlChild = ACP_NAME . '/menu/update/' . $menu->id;
                                    }
                                    break;
                                
                                default:
                                    $moduleGroup = ModuleGroup::findFirstById($moduleItemChild->module_group_id);
                                    if ($moduleGroup && !empty($moduleGroup->link) != '') {
                                        $urlChild = ACP_NAME . '/' . $moduleGroup->link;
                                    } else {
                                        $urlChild = '';
                                    }
                                      
                                    break;
                            }

                            $itemChild = $moduleItemChild->toArray();
                            $itemChild['url'] = $urlChild;
                            $itemChild['module_name'] = ($moduleItemChild->module_type == 'post') ? 'Tự soạn thảo: ' . $moduleItemChild->module_name : $moduleItemChild->module_name;

                            $itemChilds[] = $itemChild;
                        }

                        $item['child'] = $itemChilds;
                    }
                }

                // check module positon exist
                if ($moduleItem['position_id'] != $p->id && $moduleItem['position_id'] != '') {
                    $item['id'] = '';
                    $item['layout_id'] = '';
                    $item['position_id'] = '';
                    $item['active'] = '';
                    $item['sort'] = '';
                    $unsetArray[$p->code][$moduleItem['module_id']] = $item;
                } else {
                    if ($item['sort'] != null) {
                        $existTmpArray[$p->code][$moduleItem['module_id']] = $item;
                    } else {
                        $unsetArray[$p->code][$moduleItem['module_id']] = $item;
                    }
                }
            }

            $position_module_array[$p->code] = [];

            if (!empty($existTmpArray[$p->code])) {
                foreach ($existTmpArray[$p->code] as $existArray) {
                    array_push($position_module_array[$p->code], $existArray);
                }
            }

            // remove array unset duplicate
            if (isset($unsetArray[$p->code]) && !empty($unsetArray[$p->code])) {
                $unsetArray[$p->code] = array_map("unserialize", array_unique(array_map("serialize", $unsetArray[$p->code])));
                $unsetArray[$p->code] = array_values($unsetArray[$p->code]);
                $nameString[$p->code] = [];
                
                // sort by module name
                foreach ($unsetArray[$p->code] as $uArray) {
                    $nameString[$p->code][] = $uArray['module_name'];
                }
                if (!empty($nameString[$p->code])) {
                    array_multisort($nameString[$p->code], SORT_ASC, SORT_STRING, $unsetArray[$p->code]);
                }
                
                foreach ($unsetArray[$p->code] as $uArray) {
                    if (!isset($existTmpArray[$p->code][$uArray['module_id']])) {
                        array_push($position_module_array[$p->code], $uArray);
                    }
                }
            }

            $position_module_array[$p->code] = array_values($position_module_array[$p->code]);
        }

        $brgDefaults = [];
        for ($i = 1; $i <= 10; $i++) {
            $pathFile = 'assets/source/background/thumbnail/' . 'background-' . $i . '.jpg';
            $typeFile = pathinfo($pathFile, PATHINFO_EXTENSION);
            $dataFile = file_get_contents($pathFile);
            $brgDefaults[] = 'data:image/' . $typeFile . ';base64,' . base64_encode($dataFile);
        }

        $breadcrumb = '<li class="active">Cài đặt giao diện</li>';
        $this->view->breadcrumb = $breadcrumb;
        $this->view->setting = $setting;
        $this->view->layout = $layout;
        $this->view->layout_id = $layoutId;
        $this->view->module_array = $moduleArray;
        $this->view->css_item = $css_item;
        $this->view->background = $background;
        $this->view->layout_config = $layoutConfig;
        $this->view->brgDefaults = $brgDefaults;
        $this->view->position_module_array = $position_module_array;
        $this->view->list_color = $this->mainGlobal->colorDefault();
        $this->view->folder = $this->_get_subdomainFolder();
        $this->view->form = $form;
    }

    /**
     * Setting multilang google and database
     * 
     * @return View
     */
    public function languageAction()
    {
        $identity = $this->auth->getIdentity();
        $breadcrumb = '<li class="active">Đa ngôn ngữ</li>';
        $languages = Languages::find(['conditions' => 'active = "Y" AND deleted ="N"']);
        $tmpSubdomainLanguages = TmpSubdomainLanguage::findBySubdomainId($this->_get_subdomainID());
        $arrayTmp = [];
        if (count($tmpSubdomainLanguages) > 0) {
            foreach ($tmpSubdomainLanguages as $key => $tmpSubdomainLanguage) {
                $arrayTmp[] = $tmpSubdomainLanguage->language_id;
            }
        }

        // config item multilang google
        $list_config_item = ConfigItem::find([
            "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND (field = '_cf_radio_menu_google_translate' OR field = '_cf_radio_menu_language_database') AND active='Y'",
            "order" => "sort ASC, name ASC, id DESC"
        ]);

        $list_config_item_arr = [];
        if (count($list_config_item) > 0) {
            foreach ($list_config_item as $config_item) {
                $config_item_arr = [];
                if (isset($config_item->config_core->parent_id)) {
                    $config_item_arr = $config_item->toArray();
                    if ($config_item->type == 'radio' || $config_item->type == 'select' || $config_item->type == 'checkbox') {
                        $config_item_arr['list_value'] = json_decode($config_item->value, true);
                        if (!empty($config_item->config_core->guide)) {
                            $config_item_arr['guide'] = $config_item->config_core->guide;
                        }
                    }

                    $configCoreChilds= ConfigCore::find([
                        "conditions" => "parent_id = ". $config_item->config_core->id ." AND active='Y'",
                        "order" => "sort ASC, name ASC, id DESC"
                    ]);

                    if (count($configCoreChilds) > 0) {
                        $configItemChildArr = [];
                        foreach ($configCoreChilds as $key => $configCoreChild) {
                            $configItemChild = ConfigItem::findFirst([
                                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND config_core_id = $configCoreChild->id"
                            ]);
                            if ($configItemChild) {
                                $configItemChildArrChild = $configItemChild->toArray();
                                if ($configItemChild->type == 'radio' || $configItemChild->type == 'select' || $configItemChild->type == 'checkbox') {
                                    $configItemChildArrChild['list_value'] = json_decode($configItemChild->value, true);
                                    if (!empty($configItemChild->config_core->guide)) {
                                        $configItemChildArrChild['guide'] = $configItemChild->config_core->guide;
                                    }
                                }

                                $configItemChildArr[] = $configItemChildArrChild;
                            }
                        }

                        $config_item_arr['child'] = $configItemChildArr;
                    }
                }

                $list_config_item_arr[] = $config_item_arr;
            }
        }

        $messageFolder = $this->config->application->messages;
        if ($this->request->isPost()) {
            // save config
            $request = $this->request->getPost();
            if (!empty($list_config_item_arr)) {
                foreach ($list_config_item_arr as $row) {
                    $config_item = ConfigItem::findFirstById($row['id']);
                    if ($row['type'] == 'checkbox') {
                        $array_list_value = array();
                        foreach ($row['list_value'] as $key => $value) {
                            $array_list_value[] = $value;
                            if (isset($request[$row['field']])) {
                                if (in_array($value['name'], $request[$row['field']])) {
                                    $array_list_value[$key]['select'] = 1;
                                }
                                if (!in_array($value['name'], $request[$row['field']])) {
                                    $array_list_value[$key]['select'] = 0;
                                }
                            } else {
                                $array_list_value[$key]['select'] = 0;
                            }
                        }

                        $config_item->assign([
                            'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                        ]);
                        $config_item->save();
                    } else {
                        if (isset($request[$row['field']])) {
                            if ($row['type'] == 'text' || $row['type'] == 'email' || $row['type'] == 'textarea') {
                                $config_item->assign([
                                    'value' => $request[$row['field']]
                                ]);
                                $config_item->save();
                            }

                            if ($row['type'] == 'radio') {
                                $array_list_value = array();
                                foreach ($row['list_value'] as $key => $value) {
                                    $array_list_value[] = $value;
                                    if (in_array($value['name'], $request[$row['field']])) {
                                        $array_list_value[$key]['select'] = 1;
                                    }
                                    if (!in_array($value['name'], $request[$row['field']])) {
                                        $array_list_value[$key]['select'] = 0;
                                    }
                                }

                                $config_item->assign([
                                    'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                ]);
                                $config_item->save();
                            }

                            if ($row['type'] == 'select') {
                                $array_list_value = array();
                                foreach ($row['list_value'] as $key => $value) {
                                    $array_list_value[] = $value;
                                    if ($value['name'] == $request[$row['field']]) {
                                        $array_list_value[$key]['select'] = 1;
                                    } else {
                                        $array_list_value[$key]['select'] = 0;
                                    }
                                }

                                $config_item->assign([
                                    'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                ]);
                                $config_item->save();
                            }
                        }
                    }
                    
                    if (isset($row['child'])) {
                        $child = $row['child'];
                        foreach ($child as $ch) {
                            $config_item = ConfigItem::findFirstById($ch['id']);
                            $array_list_value = [];
                            if ($ch['type'] == 'checkbox') {
                                // add italia google translate
                                if ($ch['field'] == '_cf_checkbox_select_language') {
                                    if (count($ch['list_value']) == 6) {
                                        $itemItalyLang = [
                                            'name' => 'Italia',
                                            'value' => 1,
                                            'select' => 0
                                        ];

                                        array_push($ch['list_value'], $itemItalyLang);
                                    }
                                }

                                foreach ($ch['list_value'] as $key => $value) {
                                    $array_list_value[] = $value;
                                    if (isset($request[$ch['field']])) {
                                        if (in_array($value['name'], $request[$ch['field']])) {
                                            $array_list_value[$key]['select'] = 1;
                                        }
                                        if (!in_array($value['name'], $request[$ch['field']])) {
                                            $array_list_value[$key]['select'] = 0;
                                        }
                                    } else {
                                        $array_list_value[$key]['select'] = 0;
                                    }
                                }

                                $config_item->assign([
                                    'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                ]);
                                $config_item->save();
                            } else {
                                if (isset($request[$ch['field']])) {
                                    if ($ch['type'] == 'text' || $ch['type'] == 'email' || $ch['type'] == 'textarea') {
                                        $config_item->assign([
                                            'value' => $request[$ch['field']]
                                        ]);
                                        $config_item->save();
                                    }
                                    if ($ch['type'] == 'radio') {
                                        $array_list_value = array();
                                        foreach ($ch['list_value'] as $key => $value) {
                                            $array_list_value[] = $value;
                                            if (in_array($value['name'], $request[$ch['field']])) {
                                                $array_list_value[$key]['select'] = 1;
                                            }
                                            if (!in_array($value['name'], $request[$ch['field']])) {
                                                $array_list_value[$key]['select'] = 0;
                                            }
                                        }
                                        $config_item->assign([
                                            'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                        ]);
                                        $config_item->save();
                                    }
                                    if ($ch['type'] == 'select') {
                                        $array_list_value = array();
                                        foreach ($ch['list_value'] as $key => $value) {
                                            $array_list_value[] = $value;
                                            if ($value['name'] == $request[$ch['field']]) {
                                                $array_list_value[$key]['select'] = 1;
                                            } else {
                                                $array_list_value[$key]['select'] = 0;
                                            }
                                        }
                                        $config_item->assign([
                                            'value' => json_encode($array_list_value, JSON_UNESCAPED_UNICODE)
                                        ]);
                                        $config_item->save();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // save language
            $languageRequests = $this->request->getPost('language');
            TmpSubdomainLanguage::deleteByRawSql('subdomain_id ='. $this->_get_subdomainID() .'');
            if (!empty($languageRequests)) {
                $tmpSubdomainLanguage = new TmpSubdomainLanguage();
                $tmpSubdomainLanguage->subdomain_id = $this->_get_subdomainID();
                $tmpSubdomainLanguage->language_id = 1;
                $tmpSubdomainLanguage->save();

                $setting = Setting::findFirst([
                    'conditions' => 'Modules\Models\Setting.subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1'
                ]);

                $productDetails = ProductDetail::find([
                    'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1'
                ]);

                $posts = Posts::find([
                    'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1'
                ]);

                $banners = Banner::find([
                    'conditions' => 'Modules\Models\Banner.subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1'
                ]);

                $categories = Category::find([
                    'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1',
                    'order' => 'parent_id'
                ]);

                $newsMenus = Category::find([
                    'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1',
                    'order' => 'parent_id'
                ]);

                $menus = Menu::find([
                    'conditions' => 'Modules\Models\Menu.subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1'
                ]);

                if (!is_dir($messageFolder . '/subdomains/' . $this->_get_subdomainFolder())) {
                    mkdir($messageFolder . '/subdomains/' . $this->_get_subdomainFolder(), 0777);
                }

                $langFile = $messageFolder . 'subdomains/' . $this->_get_subdomainFolder() . '/vi.json';
                if (!file_exists($langFile)) {
                    copy($messageFolder . 'core/vi.json', $langFile);
                }

                foreach ($languageRequests as $languageRequest) {
                    $tmpSubdomainLanguage = new TmpSubdomainLanguage();
                    $tmpSubdomainLanguage->subdomain_id = $this->_get_subdomainID();
                    $tmpSubdomainLanguage->language_id = $languageRequest;
                    if ($tmpSubdomainLanguage->save()) {
                        $langId = $tmpSubdomainLanguage->language_id;
                        $langCode = $tmpSubdomainLanguage->language->code;
                        $langFile = $messageFolder . 'subdomains/' . $this->_get_subdomainFolder() . '/' . $langCode . '.json';
                        if (!file_exists($langFile)) {
                            copy($messageFolder . 'core/' . $langCode . '.json', $langFile);
                        }

                        $settingLang = Setting::findFirst([
                            'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = '. $langId .''
                        ]);
                        if (!$settingLang) {
                            $settingLang = new Setting();
                            $settingVi = $setting->toArray();
                            $settingVi['language_id'] = $langId;
                            $settingVi['depend_id'] = $settingVi['id'];
                            unset($settingVi['id']);
                            $settingLang->assign($settingVi);
                            $settingLang->save();
                        }

                        if (count($productDetails) > 0) {
                            foreach ($productDetails as $productDetail) {
                                $productDetailLang = ProductDetail::findFirst([
                                    'conditions' => 'subdomain_id = ' . $this->_get_subdomainID() . ' AND depend_id = '. $productDetail->id .' AND language_id = '. $langId .''
                                ]);
                                if (!$productDetailLang) {
                                    $name = $this->_trans->getTranslator('vi', $langCode, $productDetail->name);
                                    $slug = $this->general->create_slug($name);
                                    $data = [
                                        'subdomain_id' => $this->_get_subdomainID(),
                                        'language_id' => $langId,
                                        'depend_id' => $productDetail->id,
                                        'sort' => $productDetail->sort,
                                        'active' => $productDetail->active,
                                        'enable_delete' => $productDetail->enable_delete,
                                        'name' => $name,
                                        'slug' => $slug,
                                    ];
                                    $productDetailLang = new ProductDetail();
                                    $productDetailLang->assign($data);
                                    if (!$productDetailLang->save()) {
                                        foreach ($productDetailLang->getMessages() as $message) {
                                            $this->flashSession->error($message);
                                        }
                                    }
                                }
                            }
                        }

                        if (count($posts) > 0) {
                            foreach ($posts as $post) {
                                $postLang = Posts::findFirst([
                                    'conditions' => 'subdomain_id = ' . $this->_get_subdomainID() . ' AND depend_id = '. $post->id .' AND language_id = '. $langId .''
                                ]);
                                if (!$postLang) {
                                    $postVi = $post->toArray();
                                    $postVi['name'] = (!empty($post->name)) ? $this->_trans->getTranslator('vi', $langCode, $post->name) : '';
                                    $postVi['subdomain_id'] = $this->_get_subdomainID();
                                    $postVi['language_id'] = $langId;
                                    $postVi['depend_id'] = $postVi['id'];
                                    unset($postVi['id']);
                                    unset($postVi['created_at']);
                                    unset($postVi['modified_in']);
                                    $postLang = new Posts();
                                    $postLang->assign($postVi);
                                    $postLang->save();
                                }
                            }
                        }

                        if (count($banners) > 0) {
                            foreach ($banners as $banner) {
                                $bannerLang = Banner::findFirst([
                                    'conditions' => 'subdomain_id = ' . $this->_get_subdomainID() . ' AND depend_id = '. $banner->id .' AND language_id = '. $langId .''
                                ]);
                                if (!$bannerLang) {
                                    $bannerVi = $banner->toArray();
                                    $bannerVi['name'] = (!empty($banner->name)) ? $this->_trans->getTranslator('vi', $langCode, $banner->name) : '';
                                    $bannerVi['subdomain_id'] = $this->_get_subdomainID();
                                    $bannerVi['language_id'] = $langId;
                                    $bannerVi['depend_id'] = $bannerVi['id'];
                                    if ($bannerVi['photo'] != '' && file_exists('files/ads/' . $this->_get_subdomainFolder() . '/' . $bannerVi['photo'])) {
                                        $fileName = pathinfo('files/ads/' . $this->_get_subdomainFolder() . '/' . $bannerVi['photo'], PATHINFO_FILENAME);
                                        $fileExt = pathinfo('files/ads/' . $this->_get_subdomainFolder() . '/' . $bannerVi['photo'], PATHINFO_EXTENSION);

                                        $fileNameLang = $fileName . '_' . $langCode;
                                        copy('files/ads/' . $this->_get_subdomainFolder() . '/' . $bannerVi['photo'], 'files/ads/' . $this->_get_subdomainFolder() . '/' . $fileNameLang . '.' . $fileExt);
                                        $bannerVi['photo'] = $fileNameLang . '.' . $fileExt;
                                    }

                                    unset($bannerVi['id']);
                                    unset($bannerVi['created_at']);
                                    unset($bannerVi['modified_in']);
                                    $bannerLang = new Banner();
                                    $bannerLang->assign($bannerVi);
                                    $bannerLang->save();

                                    $tmpBannerBannerTypes = TmpBannerBannerType::findByBannerId($banner->id);
                                    if (count($tmpBannerBannerTypes) > 0) {
                                        foreach ($tmpBannerBannerTypes as $tmp) {
                                            $tmpVi = $tmp->toArray();
                                            $tmpVi['banner_id'] = $bannerLang->id;
                                            $tmpLang = new TmpBannerBannerType();
                                            $tmpLang->assign($tmpVi);
                                            $tmpLang->save();
                                        }
                                    }
                                }
                            }
                        }

                        if (count($categories) > 0) {
                            foreach ($categories as $category) {
                                $categoryLang = Category::findFirst([
                                    'conditions' => 'subdomain_id = ' . $this->_get_subdomainID() . ' AND depend_id = '. $category->id .' AND language_id = '. $langId .''
                                ]);

                                if (!$categoryLang) {
                                    $parentId = 0;
                                    if ($category->parent_id != 0) {
                                        $categoryParentLang = Category::findFirstByDependId($category->parent_id);
                                        $categoryParentLang = Category::findFirst([
                                            'conditions' => 'subdomain_id = ' . $this->_get_subdomainID() . ' AND depend_id = '. $category->parent_id .' AND language_id = '. $langId .''
                                        ]);

                                        if ($categoryParentLang) {
                                            $parentId = $categoryParentLang->id;
                                        }
                                    }

                                    $categoryVi = $category->toArray();
                                    $categoryVi['name'] = (!empty($category->name)) ? $this->_trans->getTranslator('vi', $langCode, $category->name) : '';
                                    $categoryVi['slug'] = $this->general->create_slug($categoryVi['name']); 
                                    $categoryVi['title'] = (!empty($category->title)) ? $this->_trans->getTranslator('vi', $langCode, $category->title) : '';
                                    $categoryVi['keywords'] = (!empty($category->keywords)) ? $this->_trans->getTranslator('vi', $langCode, $category->keywords) : '';
                                    $categoryVi['description'] = (!empty($category->description)) ? $this->_trans->getTranslator('vi', $langCode, $category->description) : '';
                                    $categoryVi['subdomain_id'] = $this->_get_subdomainID();
                                    $categoryVi['parent_id'] = $parentId;
                                    $categoryVi['language_id'] = $langId;
                                    $categoryVi['depend_id'] = $categoryVi['id'];
                                    unset($categoryVi['id']);
                                    unset($categoryVi['created_at']);
                                    unset($categoryVi['modified_in']);
                                    $categoryLang = new Category();
                                    $categoryLang->assign($categoryVi);
                                    $categoryLang->save();
                                }
                            }
                        }

                        if (count($newsMenus) > 0) {
                            foreach ($newsMenus as $newsMenu) {
                                $newsMenuLang = NewsMenu::findFirst([
                                    'conditions' => 'subdomain_id = ' . $this->_get_subdomainID() . ' AND depend_id = '. $newsMenu->id .' AND language_id = '. $langId .''
                                ]);

                                if (!$newsMenuLang) {
                                    $parentId = 0;
                                    if ($newsMenu->parent_id != 0) {
                                        $newsMenuParentLang = NewsMenu::findFirstByDependId($newsMenu->parent_id);
                                        $newsMenuParentLang = NewsMenu::findFirst([
                                            'conditions' => 'subdomain_id = ' . $this->_get_subdomainID() . ' AND depend_id = '. $newsMenu->parent_id .' AND language_id = '. $langId .''
                                        ]);

                                        if ($newsMenuParentLang) {
                                            $parentId = $newsMenuParentLang->id;
                                        }
                                    }

                                    $newsMenuVi = $newsMenu->toArray();
                                    $newsMenuVi['name'] = (!empty($newsMenu->name)) ? $this->_trans->getTranslator('vi', $langCode, $newsMenu->name) : '';
                                    $newsMenuVi['slug'] = $this->general->create_slug($newsMenuVi['name']);
                                    $newsMenuVi['summary'] = (!empty($newsMenu->summary)) ? $this->_trans->getTranslator('vi', $langCode, $newsMenu->summary) : '';
                                    $newsMenuVi['title'] = (!empty($newsMenu->title)) ? $this->_trans->getTranslator('vi', $langCode, $newsMenu->title) : '';
                                    $newsMenuVi['keywords'] = (!empty($newsMenu->keywords)) ? $this->_trans->getTranslator('vi', $langCode, $newsMenu->keywords) : '';
                                    $newsMenuVi['description'] = (!empty($newsMenu->description)) ? $this->_trans->getTranslator('vi', $langCode, $newsMenu->description) : '';
                                    $newsMenuVi['subdomain_id'] = $this->_get_subdomainID();
                                    $newsMenuVi['parent_id'] = $parentId;
                                    $newsMenuVi['language_id'] = $langId;
                                    $newsMenuVi['depend_id'] = $newsMenuVi['id'];
                                    unset($newsMenuVi['id']);
                                    unset($newsMenuVi['created_at']);
                                    unset($newsMenuVi['modified_in']);
                                    $newsMenuLang = new NewsMenu();
                                    $newsMenuLang->assign($newsMenuVi);
                                    $newsMenuLang->save();
                                }
                            }
                        }

                        if (count($menus) > 0) {
                            foreach ($menus as $menu) {
                                $menuLang = Menu::findFirst([
                                    'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND depend_id = '. $menu->id .' AND language_id = '. $langId .''
                                ]);

                                if (!$menuLang) {
                                    $menuItems = MenuItem::find([
                                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND menu_id = '. $menu->id .''
                                    ]);
                                    $menuVi = $menu->toArray();
                                    $menuVi['subdomain_id'] = $this->_get_subdomainID();
                                    $menuVi['language_id'] = $langId;
                                    $menuVi['depend_id'] = $menuVi['id'];
                                    unset($menuVi['id']);
                                    unset($menuVi['created_at']);
                                    unset($menuVi['modified_in']);

                                    $menuLang = new Menu();
                                    $menuLang->assign($menuVi);
                                    if ($menuLang->save()) {
                                        if (count($menuItems) > 0) {
                                            foreach ($menuItems as $menuItem) {
                                                $menuItemVi = $menuItem->toArray();
                                                if ($menuItemVi['module_name'] == 'index') {
                                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_nha'];
                                                }

                                                if ($menuItemVi['module_name'] == 'product') {
                                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_san_pham'];
                                                    $menuItemVi['url'] = 'product';
                                                }

                                                if ($menuItemVi['module_name'] == 'customer_comment') {
                                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_y_kien_khach_hang'];
                                                    $menuItemVi['url'] = 'comment';
                                                }

                                                if ($menuItemVi['module_name'] == 'contact') {
                                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_lien_he'];
                                                    $menuItemVi['url'] = 'contact';
                                                }

                                                if ($menuItemVi['module_name'] == 'clip') {
                                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_video'];
                                                    $menuItemVi['url'] = 'video';
                                                }

                                                if ($menuItemVi['module_name'] == 'subdomain_list') {
                                                    $menuItemVi['name'] = $this->wordTranslateData[$langCode]['_du_an'];
                                                    $menuItemVi['url'] = 'project';
                                                }

                                                if ($menuItemVi['module_name'] == 'category') {
                                                    $categoryVi = Category::findFirstById($menuItemVi['module_id']);
                                                    if ($categoryVi) {
                                                        $categoryLang = Category::findFirst([
                                                            'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND depend_id = ' . $categoryVi->id . ' AND language_id = '. $langId .''
                                                        ]);
                                                        if ($categoryLang) {
                                                            $menuItemVi['module_id'] = $categoryLang->id;
                                                            $menuItemVi['name'] = $categoryLang->name;
                                                            $menuItemVi['url'] = $categoryLang->slug;
                                                        } else {
                                                            $menuItemVi['name'] = '_update';
                                                        }
                                                    }
                                                }

                                                if ($menuItemVi['module_name'] == 'news_menu') {
                                                    $newsMenuVi = NewsMenu::findFirstById($menuItemVi['module_id']);
                                                    if ($newsMenuVi) {
                                                        $newsMenuLang = NewsMenu::findFirst([
                                                            'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND depend_id = ' . $newsMenuVi->id . ' AND language_id = '. $langId .''
                                                        ]);
                                                        if ($newsMenuLang) {
                                                            $menuItemVi['module_id'] = $newsMenuLang->id;
                                                            $menuItemVi['name'] = $newsMenuLang->name;
                                                            $menuItemVi['url'] = $newsMenuLang->slug;
                                                        } else {
                                                            $menuItemVi['name'] = '_update';
                                                        }
                                                    }
                                                }

                                                $menuItemVi['subdomain_id'] = $this->_get_subdomainID();
                                                $menuItemVi['language_id'] = $langId;
                                                $menuItemVi['menu_id'] = $menuLang->id;
                                                $menuItemVi['depend_id'] = $menuItemVi['id'];
                                                unset($menuItemVi['id']);
                                                unset($menuItemVi['created_at']);
                                                unset($menuItemVi['modified_in']);
                                                if ($menuItemVi['name'] != '_update') {
                                                    $menuItemLang = new MenuItem();
                                                    $menuItemLang->assign($menuItemVi);
                                                    if (!$menuItemLang->save()) {
                                                        foreach ($menuItemLang->getMessages() as $message) {
                                                            $this->flashSession->error($message);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        foreach ($menuLang->getMessages() as $message) {
                                            $this->flashSession->error($message);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }

            
            $this->flashSession->success($this->_message["edit"]);
            return $this->response->redirect(ACP_NAME . '/' . $this->_getControllerName() . '/language');
        }

        $this->view->list_config_item_arr = $list_config_item_arr;
        $this->view->arrayTmp = $arrayTmp;
        $this->view->languages = $languages;
        $this->view->breadcrumb = $breadcrumb;
    }

    public function deleteImateAction()
    {
        if ($this->request->getQuery('type')) {
            $setting = Setting::findFirst([
                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = 1"
            ]);
            if ($setting) {
                $urlRedirect = ACP_NAME . '/' . $this->_getControllerName() . '/content';
                switch ($this->request->getQuery('type')) {
                    case 'favicon':
                        if (!empty($setting->favicon) && file_exists('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->favicon)) {
                            unlink('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->favicon);
                            $setting->favicon = '';
                            if ($setting->save()) {
                                $settingLangs = Setting::findByDependId($setting->id);
                                if (count($settingLangs) > 0) {
                                    foreach ($settingLangs as $settingLang) {
                                        $settingLang->favicon = '';
                                        $settingLang->save();
                                    }
                                }
                            }
                        }
                       
                        break;

                    case 'logo':
                        if ($this->request->getQuery('language')) {
                            $lang = $this->request->getQuery('language');
                            $language = Languages::findFirstByCode($lang);
                            $setting = Setting::findFirst([
                                "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND language_id = $language->id"
                            ]);

                            if (!empty($setting->logo) && file_exists('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->logo)) {
                                unlink('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->logo);
                            }
                            $setting->logo = '';
                            $setting->save();
                        }
                            

                        break;

                    case 'bgr_ycbg':
                        if (!empty($setting->bgr_ycbg) && file_exists('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->bgr_ycbg)) {
                            unlink('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->bgr_ycbg);
                            $setting->bgr_ycbg = '';
                            if ($setting->save()) {
                                $settingLangs = Setting::findByDependId($setting->id);
                                if (count($settingLangs) > 0) {
                                    foreach ($settingLangs as $settingLang) {
                                        $settingLang->bgr_ycbg = '';
                                        $settingLang->save();
                                    }
                                }
                            }
                        }
                       
                        break;

                    case 'image_article_home':
                        if (!empty($setting->image_article_home) && file_exists('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->image_article_home)) {
                            unlink('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->image_article_home);
                            $setting->image_article_home = '';
                            if ($setting->save()) {
                                $settingLangs = Setting::findByDependId($setting->id);
                                if (count($settingLangs) > 0) {
                                    foreach ($settingLangs as $settingLang) {
                                        $settingLang->image_article_home = '';
                                        $settingLang->save();
                                    }
                                }
                            }
                        }
                       
                        break;

                    case 'image_meta':
                        if (!empty($setting->image_meta) && file_exists('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->image_meta)) {
                            unlink('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->image_meta);
                            $setting->image_meta = '';
                            if ($setting->save()) {
                                $settingLangs = Setting::findByDependId($setting->id);
                                if (count($settingLangs) > 0) {
                                    foreach ($settingLangs as $settingLang) {
                                        $settingLang->image_meta = '';
                                        $settingLang->save();
                                    }
                                }
                            }
                        }
                       
                        break;

                    case 'image_menu_2':
                        if (!empty($setting->image_menu_2) && file_exists('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->image_menu_2)) {
                            unlink('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->image_menu_2);
                            $setting->image_menu_2 = '';
                            if ($setting->save()) {
                                $settingLangs = Setting::findByDependId($setting->id);
                                if (count($settingLangs) > 0) {
                                    foreach ($settingLangs as $settingLang) {
                                        $settingLang->image_menu_2 = '';
                                        $settingLang->save();
                                    }
                                }
                            }
                        }
                       
                        break;

                    case 'image_article_home':
                        if (!empty($setting->image_article_home) && file_exists('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->image_article_home)) {
                            unlink('files/default/' . $this->_get_subdomainFolder() . '/' . $setting->image_article_home);
                            $setting->image_article_home = '';
                            if ($setting->save()) {
                                $settingLangs = Setting::findByDependId($setting->id);
                                if (count($settingLangs) > 0) {
                                    foreach ($settingLangs as $settingLang) {
                                        $settingLang->image_article_home = '';
                                        $settingLang->save();
                                    }
                                }
                            }
                        }
                       
                        break;
                        
                    default:
                        $this->flashSession->error('Bad require');
                        return $this->response->redirect($urlRedirect);
                        break;
                }
                
                $this->flashSession->success('Xóa hình ảnh thành công');
                return $this->response->redirect($urlRedirect);
            }
        }
    }

    public function ipAccessAction()
    {
        $identity = $this->auth->getIdentity();
        $subdomainId = $this->_get_subdomainID();
        $file = $identity['role'] == 1 ? 'counter_ip/counter-ip.txt' : 'counter_ip/counter-ip-' . $subdomainId . '.txt';
        if (file_exists($file)) {
            $user = Users::findFirstBySubdomainId($subdomainId);
            $data = '';
            $data .= file_get_contents($file);
            if ($identity['role'] != 1) {
                $agencySubdomains = Subdomain::findByCreateId($user->id);
                if ($agencySubdomains->count() > 0) {
                    foreach ($agencySubdomains as $agencySubdomain) {
                        if (file_exists('counter_ip/counter-ip-' . $agencySubdomain->id . '.txt')) {
                            $data .= file_get_contents('counter_ip/counter-ip-' . $agencySubdomain->id . '.txt');
                        }
                    }
                }
            }

            $ipLists = explode(";", $data);
            $ipLists = array_filter($ipLists);
            $ipLists = array_chunk($ipLists, 100);
            // statistic with ip
            $ipListInfos = [];
            foreach ($ipLists as $ipListChunk) {
                foreach ($ipListChunk as $ipList) {
                    $ipListElms = explode('||', $ipList);
                    if (!empty($ipListElms[1])) {
                        $ipListInfos[$ipListElms[1]][] = $ipListElms;
                    }
                }
            }
            
            $ipAcesses = [];
            $i = 0;


            foreach ($ipListInfos as $key => $ipListInfo) {
                if ($i < 1000) {
                    $ipAcesses[$key]['total'] = count($ipListInfo);

                    //get date total access
                    $todayCount = 0;
                    $yesterdayCount = 0;
                    $weekCount = 0;
                    $monthCount = 0;
                    $yearCount = 0;
                    $ipAcesses[$key]['url'] = [];

                    foreach ($ipListInfo as $iplistInf) {
                        if (date('d', strtotime($iplistInf[0])) == date('d')) {
                            $todayCount++;
                        }

                        if (date('d', strtotime($iplistInf[0])) == date('d', strtotime("-1 days"))) {
                            $yesterdayCount++;
                        }

                        if (date('W', strtotime($iplistInf[0])) == date('W')) {
                            $weekCount++;
                        }

                        if (date('n', strtotime($iplistInf[0])) == date('n')) {
                            $monthCount++;
                        }

                        if (date('Y', strtotime($iplistInf[0])) == date('Y')) {
                            $yearCount++;
                        }

                        $ipAcesses[$key]['url'][$iplistInf[2]][] = $iplistInf;
                    }

                    $ipAcesses[$key]['today'] = $todayCount;
                    $ipAcesses[$key]['yesterday'] = $yesterdayCount;
                    $ipAcesses[$key]['week'] = $weekCount;
                    $ipAcesses[$key]['month'] = $monthCount;
                    $ipAcesses[$key]['year'] = $yearCount;

                    if (!empty($ipAcesses[$key]['url'])) {
                        foreach ($ipAcesses[$key]['url'] as $keyUrl => $url) {
                            $ipAcesses[$key]['url'][$keyUrl] = count($url);
                        }

                        $ipAcesses[$key]['url'] = json_encode($ipAcesses[$key]['url']);
                    }

                    $ipNote = IpNote::findFirst([
                        'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND ip_address = "'. $key .'"'
                    ]);

                    if ($ipNote) {
                        $ipAcesses[$key]['note'] = $ipNote->note;
                    }
                }

                $i++;
            }

            //sort ip width today
            uasort($ipAcesses, function ($a, $b) {
                return $a['today'] < $b['today'];
            });

            $this->view->ipAcesses = $ipAcesses;
        }
    }

    private function imageCheck($extension)
    {
        $allowedTypes = [
            'image/gif',
            'image/jpg',
            'image/png',
            'image/bmp',
            'image/jpeg',
            'image/x-icon'
        ];

        return in_array($extension, $allowedTypes);
    }

    /**
     * get css custom
     * 
     * @param  array $dataCss 
     * @param  bolean $enableColor
     * @return string
     */
    protected function getCustomCss($dataCss, $enableColor)
    {
        $content = "";

        $bodyCss = "";
        $textareaCss = "";
        $barCss = "";
        $txtCss = "";
        $pageCss = "";
        $headerCss = "";
        $leftCss = "";
        $rightCss = "";
        $contentCss = "";
        $footerCss = "";
        $menu_top_color = '';

        //background css
        if (!empty($dataCss['font_web'])) {
            $bodyCss .= "font-family: '". $dataCss['font_web'] ."', Helvetica,Arial,sans-serif;";
            $textareaCss .= "font-family: '". $dataCss['font_web'] ."', Helvetica,Arial,sans-serif;";
        }

        if (!empty($dataCss['page_font_size'])) {
            $bodyCss .= "font-size: ". $dataCss['page_font_size'] ."px;";
        }

        if (!empty($dataCss['background_active'] == 'Y')) {
            if (!empty($dataCss['background_color'])) {
                $bodyCss .= "background-color: ". $dataCss['background_color'] .";";
            }

            if (!empty($dataCss['background_photo'])) {
                $bodyCss .= "background-image: url(/files/default/". $this->_get_subdomainFolder() ."/". $dataCss['background_photo'] .");";

                if (!empty($dataCss['background_type'])) {
                    $bodyCss .= "background-repeat: ". $dataCss['background_type'] .";";
                }

                if (!empty($dataCss['background_attachment'])) {
                    $bodyCss .= "background-attachment: ". $dataCss['background_attachment'] .";";
                }
                $bodyCss .= "background-position: top center";
            }
        }

        if (!empty($dataCss['page_width'])) {
            $pageCss .= " 
                @media (min-width: 1200px){
                    #page {
                        width: ". $dataCss['page_width'] ."px;
                    }
                }
            ";
        }

        if (!empty($dataCss['page_container_width'])) {
            $pageCss .= " 
                @media (min-width: 1200px){
                    .container {
                        width: ". $dataCss['page_container_width'] ."px;
                    }
                }
            ";
        }

        if ($enableColor == 1) {
            //bar css
            //header css
            if (!empty($dataCss['container'])) {
                $barCss .= "
.container {background: ". $dataCss['container'] .";}
";
            }

            if (!empty($dataCss['bar_web_bgr'])) {
                $bar_web_color = '';
                $bar_web_bgr_color = '';
                if (!empty($dataCss['bar_web_color'])) {
                    $bar_web_color .= 'color: '. $dataCss["bar_web_color"] .'';
                    $bar_web_bgr_color .= "
.bar_web_bgr h1, bar_web_bgr h2, bar_web_bgr h3, .bar_web_bgr a {color: ". $dataCss['bar_web_color'] .";}";
                }
                $barCss .= ".bar_web_bgr {background: ". $dataCss['bar_web_bgr'] .";$bar_web_color}
.bar_web_bgr ul, .bar_web_bgr li > ul, .bar_web_bgr li > ul > li > ul, .bar_web_bgr li > ul > li > ul > li > ul {background: ". $dataCss['bar_web_bgr'] .";}$bar_web_bgr_color
.category_bar a:before { background: ". $dataCss['bar_web_bgr'] ."; }
.bar_web_bgr .container{background: ". $dataCss['bar_web_bgr'] .";}
.bar_web_bgr.btn{border-color: ". $dataCss['bar_web_bgr'] .";}
.bar_web_bgr.btn:hover, .bar_web_bgr.btn:focus {background: ". $dataCss['bar_web_bgr'] .";border-color: ". $dataCss['bar_web_bgr'] .";}
";
            }
            if (!empty($dataCss['menu_top_color'])) {
                $menu_top_color .= '.main_menu_nav ul li a, .main_menu_nav li > ul > li > a {color: '. $dataCss["menu_top_color"] .'}';
            }
            if (!empty($dataCss['txt_web_color'])) {
                $txtCss .= ".txt_web_color {color: ". $dataCss['txt_web_color'] .";}
.txt_web_color li {color: ". $dataCss['txt_web_color'] . ";}
.txt_web_color h1, .txt_web_color h2 {color: ". $dataCss['txt_web_color'] . ";border-left: 3px solid ". $dataCss['txt_web_color'] .";}
.media-calendar-date{border-bottom: 1px solid ". $dataCss['txt_web_color'] .";}
";
            }
        }

        //header css
        if (!empty($dataCss['background'])) {
            $headerCss .= "background: ". $dataCss['header_background'] .";";
        }

        if (!empty($dataCss['header_color'])) {
            $headerCss .= "color: ". $dataCss['header_color'] .";";
        }

        if (!empty($dataCss['header_font_size'])) {
            $headerCss .= "font-size: ". $dataCss['header_font_size'] ."px;";
        }

        if (!empty($dataCss['header_margin_top'])) {
            $headerCss .= "margin-top: ". $dataCss['header_margin_top'] ."px;";
        }

        if (!empty($dataCss['header_margin_bottom'])) {
            $headerCss .= "margin-bottom: ". $dataCss['header_margin_bottom'] ."px;";
        }

        if (!empty($dataCss['header_margin_left'])) {
            $headerCss .= "margin-left: ". $dataCss['header_margin_left'] ."px;";
        }

        if (!empty($dataCss['header_margin_right'])) {
            $headerCss .= "margin-right: ". $dataCss['header_margin_right'] ."px;";
        }

        if (!empty($dataCss['header_padding_top'])) {
            $headerCss .= "padding-top: ". $dataCss['header_padding_top'] ."px;";
        }

        if (!empty($dataCss['header_padding_bottom'])) {
            $headerCss .= "padding-bottom: ". $dataCss['header_padding_bottom'] ."px;";
        }

        if (!empty($dataCss['header_padding_left'])) {
            $headerCss .= "padding-left: ". $dataCss['header_padding_left'] ."px;";
        }

        if (!empty($dataCss['header_padding_right'])) {
            $headerCss .= "padding-right: ". $dataCss['header_padding_right'] ."px;";
        }

        //left css
        if (!empty($dataCss['left_background'])) {
            $leftCss .= "background: ". $dataCss['left_background'] .";";
        }

        if (!empty($dataCss['left_color'])) {
            $leftCss .= "color: ". $dataCss['left_color'] .";";
        }

        if (!empty($dataCss['left_font_size'])) {
            $leftCss .= "font-size: ". $dataCss['left_font_size'] ."px;";
        }

        if (!empty($dataCss['left_margin_top'])) {
            $leftCss .= "margin-top: ". $dataCss['left_margin_top'] ."px;";
        }

        if (!empty($dataCss['left_margin_bottom'])) {
            $leftCss .= "margin-bottom: ". $dataCss['left_margin_bottom'] ."px;";
        }

        if (!empty($dataCss['left_margin_left'])) {
            $leftCss .= "margin-left: ". $dataCss['left_margin_left'] ."px;";
        }

        if (!empty($dataCss['left_margin_right'])) {
            $leftCss .= "margin-right: ". $dataCss['left_margin_right'] ."px;";
        }

        if (!empty($dataCss['left_padding_top'])) {
            $leftCss .= "padding-top: ". $dataCss['left_padding_top'] ."px;";
        }

        if (!empty($dataCss['left_padding_bottom'])) {
            $leftCss .= "padding-bottom: ". $dataCss['left_padding_bottom'] ."px;";
        }

        if (!empty($dataCss['left_padding_left'])) {
            $leftCss .= "padding-left: ". $dataCss['left_padding_left'] ."px;";
        }

        if (!empty($dataCss['left_padding_right'])) {
            $leftCss .= "padding-right: ". $dataCss['left_padding_right'] ."px;";
        }

        //right css
        if (!empty($dataCss['right_background'])) {
            $rightCss .= "background: ". $dataCss['right_background'] .";";
        }

        if (!empty($dataCss['right_color'])) {
            $rightCss .= "color: ". $dataCss['right_color'] .";";
        }

        if (!empty($dataCss['right_font_size'])) {
            $rightCss .= "font-size: ". $dataCss['right_font_size'] ."px;";
        }

        if (!empty($dataCss['right_margin_top'])) {
            $rightCss .= "margin-top: ". $dataCss['right_margin_top'] ."px;";
        }

        if (!empty($dataCss['right_margin_bottom'])) {
            $rightCss .= "margin-bottom: ". $dataCss['right_margin_bottom'] ."px;";
        }

        if (!empty($dataCss['right_margin_left'])) {
            $rightCss .= "margin-left: ". $dataCss['right_margin_left'] ."px;";
        }

        if (!empty($dataCss['right_margin_right'])) {
            $rightCss .= "margin-right: ". $dataCss['right_margin_right'] ."px;";
        }

        if (!empty($dataCss['right_padding_top'])) {
            $rightCss .= "padding-top: ". $dataCss['right_padding_top'] ."px;";
        }

        if (!empty($dataCss['right_padding_bottom'])) {
            $rightCss .= "padding-bottom: ". $dataCss['right_padding_bottom'] ."px;";
        }

        if (!empty($dataCss['right_padding_left'])) {
            $rightCss .= "padding-left: ". $dataCss['right_padding_left'] ."px;";
        }

        if (!empty($dataCss['right_padding_right'])) {
            $rightCss .= "padding-right: ". $dataCss['right_padding_right'] ."px;";
        }

        //content css
        if (!empty($dataCss['content_background'])) {
            $contentCss .= "background: ". $dataCss['content_background'] .";";
        }

        if (!empty($dataCss['content_color'])) {
            $contentCss .= "color: ". $dataCss['content_color'] .";";
        }

        if (!empty($dataCss['content_font_size'])) {
            $contentCss .= "font-size: ". $dataCss['content_font_size'] ."px;";
        }

        if (!empty($dataCss['content_margin_top'])) {
            $contentCss .= "margin-top: ". $dataCss['content_margin_top'] ."px;";
        }

        if (!empty($dataCss['content_margin_bottom'])) {
            $contentCss .= "margin-bottom: ". $dataCss['content_margin_bottom'] ."px;";
        }

        if (!empty($dataCss['content_margin_left'])) {
            $contentCss .= "margin-left: ". $dataCss['content_margin_left'] ."px;";
        }

        if (!empty($dataCss['content_margin_right'])) {
            $contentCss .= "margin-right: ". $dataCss['content_margin_right'] ."px;";
        }

        if (!empty($dataCss['content_padding_top'])) {
            $contentCss .= "padding-top: ". $dataCss['content_padding_top'] ."px;";
        }

        if (!empty($dataCss['content_padding_bottom'])) {
            $contentCss .= "padding-bottom: ". $dataCss['content_padding_bottom'] ."px;";
        }

        if (!empty($dataCss['content_padding_left'])) {
            $contentCss .= "padding-left: ". $dataCss['content_padding_left'] ."px;";
        }

        if (!empty($dataCss['content_padding_right'])) {
            $contentCss .= "padding-right: ". $dataCss['content_padding_right'] ."px;";
        }

        //footer css
        if (!empty($dataCss['footer_background'])) {
            $footerCss .= "background: ". $dataCss['footer_background'] .";";
        }

        if (!empty($dataCss['footer_color'])) {
            $footerCss .= "color: ". $dataCss['footer_color'] .";";
        }

        if (!empty($dataCss['footer_font_size'])) {
            $footerCss .= "font-size: ". $dataCss['footer_font_size'] ."px;";
        }

        if (!empty($dataCss['footer_margin_top'])) {
            $footerCss .= "margin-top: ". $dataCss['footer_margin_top'] ."px;";
        }

        if (!empty($dataCss['footer_margin_bottom'])) {
            $footerCss .= "margin-bottom: ". $dataCss['footer_margin_bottom'] ."px;";
        }

        if (!empty($dataCss['footer_margin_left'])) {
            $footerCss .= "margin-left: ". $dataCss['footer_margin_left'] ."px;";
        }

        if (!empty($dataCss['footer_margin_right'])) {
            $footerCss .= "margin-right: ". $dataCss['footer_margin_right'] ."px;";
        }

        if (!empty($dataCss['footer_padding_top'])) {
            $footerCss .= "padding-top: ". $dataCss['footer_padding_top'] ."px;";
        }

        if (!empty($dataCss['footer_padding_bottom'])) {
            $footerCss .= "padding-bottom: ". $dataCss['footer_padding_bottom'] ."px;";
        }

        if (!empty($dataCss['footer_padding_left'])) {
            $footerCss .= "padding-left: ". $dataCss['footer_padding_left'] ."px;";
        }

        if (!empty($dataCss['footer_padding_right'])) {
            $footerCss .= "padding-right: ". $dataCss['footer_padding_right'] ."px;";
        }

        $content .="body { $bodyCss }textarea { $textareaCss }$pageCss $barCss $txtCss header { $headerCss } $menu_top_color
#box_left_element{ $leftCss }
#box_right_element{ $rightCss }
#content{ $contentCss }
footer  { $footerCss }
";
        return $content;
    }
}
