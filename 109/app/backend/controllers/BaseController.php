<?php namespace Modules\Backend\Controllers;

use Modules\Models\ConfigGroup;
use Modules\Models\NewsType;
use Modules\Models\Subdomain;
use Modules\Models\Menu;
use Modules\Models\Users;
use Modules\Models\TmpSubdomainUser;
use Modules\Models\ModuleItem;
use Modules\Models\Product;
use Modules\Models\Setting;
use Modules\Models\TmpSubdomainLanguage;
use Modules\Models\Languages;
use Modules\Models\Orders;
use Modules\Models\Contact;
use Modules\Models\Newsletter;
use Modules\Models\CustomerMessage;
use Modules\Models\CustomerComment;
use Modules\Models\FormItem;
use Modules\Models\SubdomainRating;
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Router;
use Modules\PhalconVn\General;
use Phalcon\Cache\Backend\Memcache;
use Phalcon\Cache\Frontend\Data as FrontData;
use Modules\Translate\GoogleTranslation;
use Modules\PhalconVn\Counter;

/**
 * ControllerBase
 *
 * This is the base controller for all controllers in the application
 */
class BaseController extends Controller
{
    public function initialize()
    {
        // Never cache the served page
        $this->response->setHeader('Cache-Control', 'private, max-age=0, must-revalidate');
        
        $gerenal = new General();
        $subdomain = $this->mainGlobal->checkDomain();
        $identity = $this->auth->getIdentity();

        $counter = new Counter();
        $countOnline = $counter->count_online();

        if ($identity) {
            $static_page = NewsType::find([
                'columns' => 'id, name',
                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND static = "Y" AND active = "Y"'
            ]);

            $arr_news_type_static = array();
            if (!empty($static_page)) {
                foreach ($static_page as $row) {
                    $arr_news_type_static[] = $row->id;
                }
            }

            //get multiple page
            $multiple_page = NewsType::find([
                'columns' => 'id, name',
                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND static = "N" AND active = "Y"'
            ]);

            $arr_news_type_multiple = array();
            if (!empty($multiple_page)) {
                foreach ($multiple_page as $row) {
                    $arr_news_type_multiple[] = $row->id;
                }
            }

            //Menu
            $menu_page = Menu::find([
                'columns' => 'id, name',
                'conditions' => 'subdomain_id = '. $this->_get_subdomainID() .' AND language_id = 1 AND active = "Y"'
            ]);

            $config_group = ConfigGroup::find(
                [
                    'conditions' => 'active = "Y"',
                    'order' => 'sort ASC, id DESC'
                ]
            );

            $setting = Setting::findFirstBySubdomainId($this->_get_subdomainID());
            $subdomainCurrent = Subdomain::findFirstById($this->_get_subdomainID());
            if ($subdomainCurrent->expired_date != '0000-00-00 00:00:00') {
                $expiredDatetime = new \Datetime($subdomainCurrent->expired_date);
                $curentDatetime = new \Datetime();
                $diff = $expiredDatetime->diff($curentDatetime);
                $this->view->dayRemain = $diff->days;
            }
            
            $tmpSubdomainLanguages = TmpSubdomainLanguage::findBySubdomainId($this->_get_subdomainID());
            $languages = languages::find();
            $wordTranslateData = [];
            if (count($languages) > 0) {
                foreach ($languages as $language) {
                    $langId = $language->id;
                    $langCode = $language->code;
                    if ($langCode != 'vi') {
                        $messageFolder = $this->config->application->messages;
                        $langFile = $messageFolder . 'core/' . $langCode . '.json';
                        if (file_exists($langFile)) {
                            $wordTranslateData[$langCode] = json_decode(file_get_contents($langFile), true);
                        }
                    }
                }
            }

            // check has order data
            $user = Users::findFirstBySubdomainId($this->_get_subdomainID());
            if ($user->role == 3) {
                $listSubName = $this->mainGlobal->getConfigKerNel('_cf_kernel_text_domain_view_order');
                if (!empty($listSubName)) {
                    $subs = explode(',', $listSubName);
                    $listId[] = $this->_get_subdomainID();
                    foreach ($subs as $sub) {
                        $subdomainSub = Subdomain::findFirstByName($sub);
                        if ($subdomainSub) {
                            $listId[] = $subdomainSub->id;
                        }
                    }

                    $listId = count($listId) > 0  ? implode(',', $listId) : $listId[0];

                    $subdomains = Subdomain::find([
                        'conditions' => 'id IN ('. $listId .')'
                    ]);

                    $list = $this->modelsManager->createBuilder()
                        ->from(MODELS . '\Orders')
                        ->where("subdomain_id IN ($listId) AND deleted = 'N'")
                        ->orderBy("id DESC")->getQuery()->getSingleResult();


                    $contact = Contact::findFirst(
                        array(
                            "order" => "id DESC",
                            "conditions" => "subdomain_id IN ($listId) AND deleted = 'N'"
                        )
                    );

                    $newsletter = Newsletter::findFirst(
                        array(
                            "order" => "id DESC",
                            "conditions" => "subdomain_id IN ($listId) AND deleted = 'N'"
                        )
                    );

                    $customerMessages = $this->modelsManager->createBuilder()
                        ->from(MODELS . '\CustomerMessage')
                        ->where("subdomain_id IN ($listId) AND deleted = 'N'")
                        ->orderBy("id DESC")->getQuery()->getSingleResult();

                    $frmItemYcbg = $this->modelsManager->createBuilder()
                        ->from(MODELS . '\FormItem')
                        ->where("subdomain_id IN ($listId) AND form_group_id = 1 AND deleted = 'N'")
                        ->orderBy("id DESC")->getQuery()->getSingleResult();

                    $frmItemFastRegister = FormItem::findFirst(
                        array(
                            "order" => "id DESC",
                            "conditions" => "subdomain_id IN ($listId) AND form_group_id = 2 AND deleted = 'N'"
                        )
                    );

                    $customerComments = CustomerComment::findFirst(
                        array(
                            "order" => "id DESC",
                            "conditions" => "subdomain_id IN ($listId) AND deleted = 'N'"
                        )
                    );
                }
            } else {
                $list = $this->modelsManager->createBuilder()
                        ->from(MODELS . '\Orders')
                        ->where("subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'")
                        ->orderBy("id DESC")->getQuery()->getSingleResult();

                $contact = Contact::findFirst(
                    array(
                        "order" => "id DESC",
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
                    )
                );

                $newsletter = Newsletter::findFirst(
                    array(
                        "order" => "id DESC",
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
                    )
                );

                $customerMessages = $this->modelsManager->createBuilder()
                        ->from(MODELS . '\CustomerMessage')
                        ->where("subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'")
                        ->orderBy("id DESC")->getQuery()->getSingleResult();

                $frmItemYcbg = $this->modelsManager->createBuilder()
                        ->from(MODELS . '\FormItem')
                        ->where("subdomain_id = ". $this->_get_subdomainID() ." AND form_group_id = 1 AND deleted = 'N'")
                        ->orderBy("id DESC")->getQuery()->getSingleResult();

                $frmItemFastRegister = FormItem::findFirst(
                    array(
                        "order" => "id DESC",
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND form_group_id = 2 AND deleted = 'N'"
                    )
                );

                $customerComments = CustomerComment::findFirst(
                    array(
                        "order" => "id DESC",
                        "conditions" => "subdomain_id = ". $this->_get_subdomainID() ." AND deleted = 'N'"
                    )
                );
            }

            $hashOrder = false;
            if ($list || $contact || $newsletter || $customerMessages || $frmItemYcbg || $frmItemFastRegister || $customerComments) {
                $hashOrder = true;
            }

            // get subdomain rating
            $subdomainRate = [];
            $subdomainRatings = SubdomainRating::find([
                "conditions" => "user_id = ". $identity['id'] ."",
                "order" => "rate DESC"
            ]);
            if (count($subdomainRatings) > 0) {
                foreach ($subdomainRatings as $subdomainRating) {
                    $subdomainRate[$subdomainRating->subdomain_id] = $subdomainRating->rate;
                }
            }

            $sessionSubdomain = $this->session->get("subdomain-child");
            $subdomainChild = Subdomain::findFirstById($sessionSubdomain['subdomain_id']);

            $this->view->subdomain = $subdomain;
            $this->view->session_subdomain = $sessionSubdomain;
            $this->view->sessionSubdomain = $sessionSubdomain;
            $this->view->subdomainChild = $subdomainChild;
            $this->view->hashOrder = $hashOrder;
            $this->view->subdomainCurrent = $subdomainCurrent;
            $this->view->subdomainRate = $this->subdomainRate = $subdomainRate;
            $this->view->static_page = $static_page;
            $this->view->setting_page = $setting;
            $this->view->arr_news_type_static = $arr_news_type_static;
            $this->view->multiple_page = $multiple_page;
            $this->view->arr_news_type_multiple = $arr_news_type_multiple;
            $this->view->config_group = $config_group;
            $this->view->menu_page = $menu_page;
            $this->view->count_online = $countOnline;
            $this->view->tmpSubdomainLanguages = $this->_tmpSubdomainLanguages = $tmpSubdomainLanguages;
            $this->view->wordTranslateData = $this->wordTranslateData = $wordTranslateData;
            $this->view->word = $this->_word = $this->word_translate->getWordTranslationSubdomain($this->_get_subdomainID());
            $this->view->cf = $this->config_service->getConfigItemSubdomain($this->_get_subdomainID());
            $this->view->isNotDeleteOrder = $this->isNotDeleteOrder = $this->mainGlobal->isNotDeleteOrder();
        }
        
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

        $this->_trans = new GoogleTranslation();
        $this->view->identity = $identity;
        
        $this->view->DOMAIN = $gerenal->get_domain(HTTP_HOST);
        $this->view->time            = time();
        $this->view->HOST = HOST;
        $this->view->appType = getenv('APP_TYPE');
        $this->view->HTTP_HOST = HTTP_HOST;
        $this->view->ACP_NAME = ACP_NAME;
        $this->view->APP_SOCKET = getenv('APP_SOCKET');
        $this->view->APP_ENV = getenv('APP_ENV');
        $this->view->ROOT_DOMAIN = ROOT_DOMAIN;
        $this->view->protocol = $protocol;
        
        $this->view->controller_name = $this->_getControllerName();
        $this->view->action_name = $this->_getActionName();
        $this->view->SUB_FOLDER = $this->_get_subdomainFolder();
        $this->view->setTemplateBefore('private');
    }
    
    public function onConstruct()
    {
        $this->general = new General();
        $identity = $this->auth->getIdentity();
        if (!empty($identity)) {
            $this->_subdomain_id = $this->_get_subdomainID();
            // $this->_subdomain_id = $this->mainGlobal->getDomainId();
            // $this->_config = $this->config_service->getConfigItem();
        }

        $this->_config_kernel = $this->mainGlobal->getConfigKernels();
    }
    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $subdomain = $this->mainGlobal->checkDomain();
        $controllerName = $dispatcher->getControllerName();

        //Only check permissions on private controllers
        if ($this->acl->isPrivate($controllerName)) {
            $urlRedirectBack = ($dispatcher->getActionName() != 'index') ? $dispatcher->getControllerName() . '/' . $dispatcher->getActionName() : $dispatcher->getControllerName();

            $urlRedirectBack = $dispatcher->getControllerName();
            if ($dispatcher->getActionName() != 'index') {
                $urlRedirectBack = $dispatcher->getControllerName() . '/' . $dispatcher->getActionName();
                $params = $dispatcher->getParams();
                if (!empty($params)) {
                    foreach ($params as $param) {
                        $urlRedirectBack .= '/' . $param;
                    }
                }
            }

            $this->session->set('url_redirect_back', $urlRedirectBack);
            //Get the current identity
            $identity = $this->auth->getIdentity();

            //If there is no identity available the user is redirected to index/index
            if (!is_array($identity)) {
                $this->flash->notice('Bạn không có quyền truy cập Module này');

                return $dispatcher->forward(array(
                    'module' => 'backend',
                    'controller' => 'index',
                    'action' => 'login'
                ));
            }

            //Check if the user have permission to the current option
            /*$actionName = $dispatcher->getActionName();
            if (!$this->acl->isAllowed($identity['profile'], $controllerName, $actionName)) {
                $this->flash->notice('Bạn không có quyền truy cập Module này');

                if ($this->acl->isAllowed($identity['profile'], $controllerName, 'index')) {

                                    $dispatcher->forward(array(
                                        'controller' => $controllerName,
                                        'action' => 'index'
                                    ));
                } else {
                                    $dispatcher->forward(array(
                                        'module' => 'backend',
                                        'controller' => 'index',
                                        'action' => 'index'
                                    ));
                }

                return false;
            }*/
        }
    }

    public function print_array($array = array())
    {
        echo '<pre>';
        print_r($array);
        echo '<pre>';
    }

    public function get_domain()
    {
        $host_link = '//'.$_SERVER['HTTP_HOST'];
        $general = new General();
        $domain = $general->get_domain($host_link);
        $domain_array = explode(".", $domain);
        $host_array = explode(".", str_replace("//", "", $host_link));
        if ($host_array[0] != $domain_array[0]) {
            return $host_array[0];
        } else {
            return '@';
        }
    }

    public function _getControllerName()
    {
        return $this->router->getControllerName();
    }

    public function _getActionName()
    {
        return $this->router->getActionName();
    }

    protected function extFileCheck($extension)
    {
        $allowedTypes = [
            'image/gif',
            'image/jpg',
            'image/png',
            'image/bmp',
            'image/jpeg',
            'image/webp'
        ];

        return in_array($extension, $allowedTypes);
    }

    protected function getMessage()
    {
        $message = array();
        if ($this->_get_subdomainAuthID() != $this->_get_subdomainID()) {
            $message["add"] = "Thêm mới thành công cho tên miền " . $this->_get_subdomainName();
            $message["edit"] = "Cập nhật dữ liệu thành công cho tên miền " . $this->_get_subdomainName();
            $message["show"] = "Hiển thị dữ liệu thành công cho tên miền " . $this->_get_subdomainName();
            $message["hide"] = "Ẩn dữ liệu thành công cho tên miền " . $this->_get_subdomainName();
            $message["delete"] = "Xóa dữ liệu thành công cho tên miền " . $this->_get_subdomainName();
        } else {
            $message["add"] = "Thêm mới thành công";
            $message["edit"] = "Cập nhật dữ liệu thành công";
            $message["show"] = "Hiển thị dữ liệu thành công";
            $message["hide"] = "Ẩn dữ liệu thành công";
            $message["delete"] = "Xóa dữ liệu thành công";
        }

        return $message;
    }

    protected function _get_subdomainAuthID()
    {
        $identity = $this->session->get('auth-identity');
        return $identity['subdomain_id'];
    }

    protected function _get_subdomainID()
    {
        $identity = $this->session->get('subdomain-child');
        return $identity['subdomain_id'];
    }

    protected function _get_subdomainName()
    {
        $identity = $this->session->get('subdomain-child');
        return $identity['subdomain_name'];
    }

    protected function _get_subdomainFolder()
    {
        $identity = $this->session->get('subdomain-child');
        return $identity['folder'];
    }

    /*public function afterExecuteRoute(Dispatcher $dispatcher)
    {

    }*/
}
