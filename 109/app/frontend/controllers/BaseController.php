<?php

namespace Modules\Frontend\Controllers;

use Modules\Models\Banner;
use Modules\Models\Category;
use Modules\Models\CategoryVideo;
use Modules\Models\News;
use Modules\Models\Setting;
use Modules\Models\ConfigItem;
use Modules\Models\Subdomain;
use Modules\Models\Domain;
use Modules\Models\Video;
use Modules\Models\Tags;
use Modules\Models\TextLink;
use Modules\Models\Clip;
use Modules\Models\LayoutConfig;
use Modules\Models\TmpModuleGroupLayout;
use Modules\Models\TmpLayoutModule;
use Modules\Models\TmpSubdomainLanguage;
use Modules\Models\Languages;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;

use Modules\Forms\Frontend\PageCustomerMessageForm;
use Modules\Forms\Frontend\FormItemForm;
use Modules\Forms\Frontend\CustomerCommentForm;
use Modules\Forms\Frontend\PageContactForm;
use Modules\Helpers\FormHelper;

/**
 * ControllerBase
 *
 * This is the base controller for all controllers in the application
 */
class BaseController extends Controller
{
    public function initialize()
    {
        // Never cache the served pagehop
        $this->response->setHeader('Cache-Control', 'private, max-age=0, must-revalidate');

        $countOnline = $this->counter->count_online();
        // $countIpOnline = $counter->count_ip_online();

        $subdomain = $this->mainGlobal->getDomainInfo();
        if (!$subdomain) {
            die;
        }

        $domain = Domain::findFirstBySubdomainId($subdomain->id);

        $tmpSubdomainLanguages = TmpSubdomainLanguage::findBySubdomainId($subdomain->id);
        $arrLanguegeId = [];
        $languageUrls = [];
        if (count($tmpSubdomainLanguages) > 0) {
            foreach ($tmpSubdomainLanguages as $tmpSubdomainLanguage) {
                $langCode = $tmpSubdomainLanguage->language->code;
                $arrLanguegeId[] = $tmpSubdomainLanguage->language_id;
                $languageUrls[$langCode] = ($langCode == 'vi') ? $this->tag->site_url() : $this->tag->site_url($langCode);
            }
        }

        $languageCode = ($this->dispatcher->getParam("language")) ? $this->dispatcher->getParam("language") : 'vi';
        $languageInfo = Languages::findFirstByCode($languageCode);
        $languageId = $languageInfo->id;
        if ($languageCode != 'vi') {
            if (!in_array($languageId, $arrLanguegeId)) {
                return $this->response->redirect('/');
            }
        }

        $message = "";
        if (!$subdomain) {
            die("Tên miền chưa được thêm vào hệ thống");
        } elseif ($subdomain->closed == 'Y') {
            die();
        } elseif ($subdomain->deleted == "Y" || $subdomain->suspended == "Y") {
            $message = "Website đang bảo trì. Vui lòng liên hệ nhà phát triển website để được hỗ trợ.";
        } elseif ($subdomain->active == "N") {
            if ($domain && $domain->name == HOST) {
                $message = "Website chưa được kích hoạt. Vui lòng liên hệ nhà phát triển website để được hỗ trợ.";
            }
        } elseif ($subdomain->active == "Y" && $subdomain->expired_date < date("Y-m-d H:i:s")) {
            $message = "Website đang bảo trì. Vui lòng liên hệ nhà phát triển website để được hỗ trợ.";
        }

        $setting = $this->settingRepository->getItemByLangId($languageId);

        switch ($setting->layout_id) {
            case '1':
                $layoutConfig = LayoutConfig::findFirst([
                    "conditions" => "subdomain_id = ". $subdomain->id ." AND layout_id = 1"
                ]);
                $layout = 'demo01';

                break;

            case '2':
                $layoutConfig = LayoutConfig::findFirst([
                    "conditions" => "subdomain_id = ". $subdomain->id ." AND layout_id = 2"
                ]);
                if ($layoutConfig->hide_left == 'Y' && $layoutConfig->hide_right == 'Y') {
                    $layout = 'demo01';
                } elseif ($layoutConfig->hide_left == 'Y' && $layoutConfig->hide_right == 'N') {
                    $layout = 'demo04';
                } elseif ($layoutConfig->hide_left == 'N' && $layoutConfig->hide_right == 'Y') {
                    $layout = 'demo03';
                } else {
                    $layout = 'demo02';
                }

                break;

            case '3':
                $layoutConfig = LayoutConfig::findFirst([
                    "conditions" => "subdomain_id = ". $subdomain->id ." AND layout_id = 3"
                ]);
                $layout = 'demo03';

                break;

            case '4':
                $layoutConfig = LayoutConfig::findFirst([
                    "conditions" => "subdomain_id = ". $subdomain->id ." AND layout_id = 4"
                ]);
                $layout = 'demo04';

                break;

            default:
                $layoutConfig = LayoutConfig::findFirst([
                    "conditions" => "subdomain_id = ". $subdomain->id ." AND layout_id = 1"
                ]);
                $layout = 'demo01';
                
                break;
        }

        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        
        $favicon = (!empty($setting->favicon)) ? '/files/default/' . $subdomain->folder . '/' . $setting->favicon : '/assets/images/favicon.ico';
        if (!empty($setting->image_meta)) {
            $imageMeta = PROTOCOL . HOST . '/' . _upload_default . $subdomain->folder . '/' . $setting->image_meta;
        } elseif ($setting->logo) {
            $imageMeta = PROTOCOL . HOST . '/' . _upload_default . $subdomain->folder . '/' . $setting->logo;
        } else {
            $imageMeta = '';
        }

        $title = (!empty($setting->title)) ? $setting->title : $setting->name;

        if ($subdomain->expired_date != '0000-00-00 00:00:00') {
            $expiredDatetime = new \Datetime($subdomain->expired_date);
            $curentDatetime = new \Datetime();
            $diff = $expiredDatetime->diff($curentDatetime);
            $this->view->dayRemain = $diff->days;
        }

        $this->view->setTemplateBefore($layout);
        $this->view->word = $this->word_translate->getWordTranslation();
        $configItems = $this->config_service->getConfigItem();
        $this->view->cf = $configItems;

        // get hotline
        $hotline = $this->redis_service->getHotlineAuto() !== null ? $this->redis_service->getHotlineAuto() : $setting->hotline;

        // get phoneAlo
        $phoneAlo = $this->redis_service->getHotlineAuto('_txt_phone_alo') !== null ? $this->redis_service->getHotlineAuto('_txt_phone_alo') : $configItems['_txt_phone_alo'];

        $hotlineZalo = $this->redis_service->getHotlineAuto('_cf_text_link_zalo') !== null ? $this->redis_service->getHotlineAuto('_cf_text_link_zalo') : $configItems['_cf_text_link_zalo'];

        $hotlineNumber = $this->redis_service->getHotlineAuto('_cf_text_hotline_number') !== null ? $this->redis_service->getHotlineAuto('_cf_text_hotline_number') : $configItems['_cf_text_hotline_number'];

        $messageLangDefault = isset($configItems['_cf_radio_select_message_lang_default']) ? $configItems['_cf_radio_select_message_lang_default'] : 'vi';

        $this->view->HOST = HOST;
        $this->view->APP_SOCKET = getenv('APP_SOCKET');
        $this->view->APP_ENV = getenv('APP_ENV');
        $this->view->favicon = $favicon;
        $this->view->protocol = $protocol;
        $this->view->DOMAIN = $this->general->get_domain(HTTP_HOST);
        $this->view->ROOT_DOMAIN = ROOT_DOMAIN;
        $this->view->subdomain = $this->subdomain = $subdomain;
        $this->view->count_online = $countOnline;
        $this->view->message = $message;
        $this->view->layout = $setting->layout_id;
        $this->view->layout_config = $layoutConfig;
        $this->view->setting = $setting;
        $this->view->title = $title;
        $this->view->keywords = $setting->keywords;
        $this->view->description = $setting->description;
        $this->view->image_meta = $imageMeta;
        $this->view->hotline = $hotline;
        $this->view->phoneAlo = $phoneAlo;
        $this->view->hotlineZalo = $hotlineZalo;
        $this->view->hotlineNumber = $hotlineNumber;
        $this->view->c_s_form = new PageCustomerMessageForm();
        $this->view->frm_item_form = new FormItemForm();
        $this->view->c_c_form = new CustomerCommentForm();
        $this->view->contact_form = new PageContactForm();
        $this->view->time            = time();
        $this->view->FILTER_VALIDATE_URL            = FILTER_VALIDATE_URL;
        $this->view->tmpSubdomainLanguages = $this->_tmpSubdomainLanguages = $tmpSubdomainLanguages;
        $this->view->languageCode = $this->languageCode = $languageCode;
        $this->view->languageInfo = $this->languageInfo = $languageInfo;
        $this->view->languageId = $this->languageId = $languageId;
        $this->view->languageUrls = $languageUrls;
        $this->view->messageLangDefault = $messageLangDefault;
    }

    public function onConstruct()
    {
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
        $this->_config = $this->config_service->getConfigItem();
        $this->_word = $this->word_translate->getWordTranslation();
        $this->_config_kernel = $this->mainGlobal->getConfigKernels();
        $languageCode = ($this->dispatcher->getParam("language")) ? $this->dispatcher->getParam("language") : 'vi';
        $languageInfo = Languages::findFirstByCode($languageCode);
        $languageId = $languageInfo->id;
        $this->languageCode = $languageCode;
        $this->languageInfo = $languageInfo;
        $this->languageId = $languageId;
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $controllerName = $dispatcher->getControllerName();
    }

    public function print_array($array = array())
    {
        echo '<pre>';
        print_r($array);
        echo '<pre>';
    }

    public function _getControllerName()
    {
        return $this->router->getControllerName();
    }

    public function _getActionName()
    {
        return $this->router->getActionName();
    }

    protected function extFileDocumentCheck($extension)
    {
        $allowedTypes = [
            'application/pdf',
            'application/octet-stream',
            'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        return in_array($extension, $allowedTypes);
    }

    protected function extImageCheck($extension)
    {
        $allowedTypes = [
            'image/jpg',
            'image/jpeg',
            'image/png',
            'image/gif',
        ];

        return in_array($extension, $allowedTypes);
    }

    /* public function afterExecuteRoute(Dispatcher $dispatcher)
      {

      } */
}
