<?php
namespace Modules\PhalconVn;

use Modules\Models\Category;
use Modules\Models\Clip;
use Modules\Models\ConfigItem;
use Modules\Models\ConfigKernel;
use Modules\Models\News;
use Modules\Models\NewsCategory;
use Modules\Models\NewsType;
use Modules\Models\NewsMenu;
use Modules\Models\Posts;
use Modules\Models\Product;
use Modules\Models\Subdomain;
use Modules\Models\Domain;
use Modules\Models\ModuleItem;
use Modules\Models\LayoutConfig;
use Modules\Models\Setting;
use Modules\Models\LandingPage;
use Modules\Helpers\FormHelper;
use Phalcon\Session\Adapter\Files as SessionAdapter;

class MainGlobal
{

    public static $instance;

    public static function getInstance()
    {
        if (!isset(MainGlobal::$instance))
            MainGlobal::$instance = new MainGlobal();
        
        return MainGlobal::$instance;
    }
    
    public function __construct()
    {
        $this->session = new SessionAdapter();
    }
    public function getRootDomain()
    {
        $host = $_SERVER['HTTP_HOST'];
        preg_match("/[^\.\/]+\.[^\.\/]+$/", $host, $matches);
        return $matches[0];
    }
    public function getFullUrl()
    {
        $gerenal = new General();
        return $gerenal->get_domain(HTTP_HOST);
    }
    public function getCurrentUrl()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        return $url;
    }
    public function colorDefault()
    {
        $listColor = array("#a94442", "#1f88e4", "#3c763d", "#8a6d3b", "#f49103", "#ebebeb", "#777", "#0e5841", "#fac");
        return $listColor;
    }

    /**
     * check domain exist
     * @return mixed|false
     */
    public function checkDomain()
    {
        $subdomain = $this->getDomainInfo();
        if ($subdomain) {
            return $subdomain;
        }

        return false;
    }

    /**
     * get domain info
     * @return false|mixed
     */
    public function getDomainInfo()
    {
        $gerenal = new General();
        $domain = $gerenal->get_domain(HTTP_HOST);
        $arr_host = explode('.', HOST);
        $arr_domain = explode('.', $domain);
        $hostName = ($arr_host[0] != 'www') ? $arr_host[0] : $arr_host[1];
        $hostNameDomain = ($arr_domain[0] != 'www') ? $arr_domain[0] : $arr_domain[1];
        $subdomain = false;
        if ($domain == ROOT_DOMAIN && $hostName != $hostNameDomain) {
            $sub = $hostName;
            $key = 'subdomains:' . $sub;
            $hasKey = __FUNCTION__;
            $subdomain = $this->_getHasKeyValue($key, $hasKey);
            if ($subdomain === null) {
                $subdomain = Subdomain::findFirst([
                    'conditions' => 'name = "'. $sub .'" AND display = "Y"'
                ]);

                $this->_setHasKeyValue($key, $hasKey, $subdomain);
            }
        } else {
            // check last character equal (.)
            $host = $s = rtrim(HOST, ".");
            $domain = Domain::findFirst([
                "columns" => "subdomain_id",
                "conditions" => "name = '" . str_replace('www.', '', $host) . "'"
            ]);
            $key = 'subdomain:' . $domain->subdomain_id;
            $hasKey = __FUNCTION__;
            $subdomain = $this->_getHasKeyValue($key, $hasKey);
            if ($subdomain === null) {
                $subdomain = Subdomain::findFirstById($domain->subdomain_id);
                $this->_setHasKeyValue($key, $hasKey, $subdomain);
            }
        }
        
        return $subdomain;
    }

    public function getDomainId()
    {
        $subdomain = $this->getDomainInfo();
        /*if (empty($subdomain)) {
            $domain = Domain::findFirst([
                "columns" => "id",
                "conditions" => "name = '". HOST ."'"
            ]);
            if ($domain) {
                $subdomain = Subdomain::findFirst([
                    "columns" => "id",
                    "conditions" => "id = $domain->subdomain_id"
                ]);
                return $subdomain->id;
            }
        }*/

        if ($subdomain) {
            return $subdomain->id;
        }
    }
    public function getDomainFolder()
    {
        $subdomain = $this->getDomainInfo();
        /*if (empty($subdomain)) {
            $domain = Domain::findFirstByName(HOST);
            if ($domain) {
                $subdomain = Subdomain::findFirstById($domain->subdomain_id);
            }
        }*/

        if ($subdomain) {
            return $subdomain->folder;
        }
    }
    public function getDomainCustomerInfo()
    {
        $gerenal = new General();
        $domain = $gerenal->get_domain(HTTP_HOST);
        $arr_host = explode('.', HOST);
        $arr_domain = explode('.', $domain);
        $hostName = ($arr_host[0] != 'www') ? $arr_host[0] : $arr_host[1];
        if ($hostName == $arr_domain[0]) {
            $domainCustomer = Domain::findFirstByName('' . str_replace('www.', '', HOST) . '');

            return $domainCustomer;
        } else {
            return false;
        }
    }
    public function urlDefault()
    {
        return array("tai-khoan", "dang-ky", "dang-nhap", "gio-hang", "thanh-toan", "lien-he", "video", "clip", "dat-hang-thanh-cong", "san-pham", "hi", "auth-login", "y-kien-khach-hang", "du-an-da-thuc-hien");
    }
    public function validateUrlPageCreate($slug, $langId = 1)
    {
        $subdomainId = $this->getDomainIdAdmin();
        $urlDefault = $this->urlDefault();
        if (in_array($slug, $urlDefault)) {
            return false;
        }
        $count_category = Category::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        $count_product = Product::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        $count_news_type = NewsType::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND slug = '$slug' AND deleted = 'N'", ]);
        $count_news_categrory = NewsCategory::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND slug = '$slug' AND deleted = 'N'", ]);
        $count_news = News::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        $count_clip = Clip::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        $count_news_menu = NewsMenu::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        $count_landing_page = LandingPage::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        if (count($count_category) > 0 || count($count_product) > 0 || count($count_news_type) > 0 || count($count_news_categrory) > 0 || count($count_news) > 0 || count($count_clip) > 0 || count($count_news_menu) > 0 || count($count_landing_page) > 0) {
            return false;
        }
        return true;
    }
    public function validateUrlPageUpdate($id, $slug, $table, $langId = 1)
    {
        $subdomainId = $this->getDomainIdAdmin();
        $urlDefault = $this->urlDefault();
        if (in_array($slug, $urlDefault)) {
            return false;
        }
        if ($table == "category") {
            $count_category = Category::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND id != $id AND deleted = 'N'", ]);
        } else {
            $count_category = Category::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        }

        if ($table == "product") {
            $count_product = Product::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND  id != $id AND deleted = 'N'", ]);
        } else {
            $count_product = Product::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        }

        if ($table == "news_type") {
            $count_news_type = NewsType::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND slug = '$slug' AND id != $id AND deleted = 'N'", ]);
        } else {
            $count_news_type = NewsType::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND slug = '$slug' AND deleted = 'N'", ]);
        }

        if ($table == "news_category") {
            $count_news_categrory = NewsCategory::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND slug = '$slug' AND id != $id AND deleted = 'N'", ]);
        } else {
            $count_news_categrory = NewsCategory::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND slug = '$slug' AND deleted = 'N'", ]);
        }

        if ($table == "news") {
            $count_news = News::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND id != $id AND deleted = 'N'", ]);
        } else {
            $count_news = News::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        }

        if ($table == "clip") {
            $count_clip = Clip::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND id != $id AND deleted = 'N'", ]);
        } else {
            $count_clip = Clip::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        }

        if ($table == "news_menu") {
            $count_news_menu = NewsMenu::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND id != $id AND deleted = 'N'", ]);
        } else {
            $count_news_menu = NewsMenu::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        }

        if ($table == "landing_page") {
            $count_landing_page = LandingPage::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND id != $id AND deleted = 'N'", ]);
        } else {
            $count_landing_page = LandingPage::find(["columns" => "id", "conditions" => "subdomain_id = $subdomainId AND language_id = $langId AND slug = '$slug' AND deleted = 'N'", ]);
        }

        if (count($count_category) > 0 || count($count_product) > 0 || count($count_news_type) > 0 || count($count_news_categrory) > 0 || count($count_news) > 0 || count($count_clip) > 0 || count($count_news_menu) > 0 || count($count_landing_page) > 0) {
            return false;
        }

        return true;
    }

    public function getPost($postModuleid)
    {
        $moduleItemCurrent = ModuleItem::findFirstById($postModuleid);
        if ($moduleItemCurrent->subdomain_id != $this->getDomainId()) {
            $moduleItem = ModuleItem::findFirst([
                "conditions" => "subdomain_id = ". $this->getDomainId() ." AND type = '". $moduleItemCurrent->type ."' AND active = 'Y' AND deleted = 'N'"
            ]);
            $moduleItemId = $moduleItem->id;
        } else {
            $moduleItemId = $postModuleid;
        }
        return Posts::findFirst(["columns" => "id, name, content, messenger_form", "conditions" => "module_item_id = $moduleItemId AND active = 'Y' AND deleted = 'N'"]);
    }
    public function getPostFromId($id)
    {
        return Posts::findFirst(["columns" => "id, name, content, messenger_form", "conditions" => "id = $id AND active = 'Y' AND deleted = 'N'"]);
    }
    public function getDomainIdAdmin()
    {
        $sesionSubdomain = $this->session->get('subdomain-child');
       
        return $sesionSubdomain['subdomain_id'];
    }
    public function getConfigItem($field)
    {
        $configItem = ConfigItem::findFirst(["columns" => "id, config_group_id name, field, value, type", "conditions" => "subdomain_id = " . $this->getDomainId() . " AND field = '$field' AND active = 'Y' AND deleted = 'N'"]);
        if (!empty($configItem)) {
            switch ($configItem->type) {
                case 'checkbox':
                    $value = json_decode($configItem->value);
                    $data = [];
                    foreach ($value as $row) {
                        if ($row->value == 1 && $row->select == 1) {
                            $data[] = $row->name;
                        }
                    }
                    return $data;

                case 'radio':
                    $value = json_decode($configItem->value);
                    $ok = false;
                    foreach ($value as $row) {
                        if ($row->value == 1 && $row->select == 1) {
                            $ok = true;
                        }
                    }

                    return $ok;
                    break;

                case 'select':
                    $res = 0;
                    $value = json_decode($configItem->value);
                    if (!empty($value)) {
                        foreach ($value as $row) {
                            if ($row->select == 1) {
                                $res = $row->value;
                            }
                        }
                    }
                    return $res;
                    break;

                case 'text':
                    return $configItem->value;
                break;
            }
        }
    }

    public function getConfigKernel($field)
    {
        $configItem = ConfigKernel::findFirst(["columns" => "id, name, field, value, type", "conditions" => "field = '$field' AND active = 'Y' AND deleted = 'N'"]);
        if (!empty($configItem)) {
            switch ($configItem->type) {
                case 'radio':
                    $value = json_decode($configItem->value);
                    $ok = false;
                    foreach ($value as $row) {
                        if ($row->value == 1 && $row->select == 1) {
                            $ok = true;
                        }
                    }
                    return $ok;
                break;
                case 'text':
                case 'email':
                case 'textarea':
                    return $configItem->value;
                break;
            }
        }
    }

    public function getConfigKernels()
    {
        $configs = ConfigKernel::find([
            "columns" => "id, name, field, value, type",
            "conditions" => "active = 'Y' AND deleted = 'N'"
        ]);

        $result = [];
        if (count($configs) > 0) {
            foreach ($configs as $config) {
                switch ($config->type) {
                    case 'radio':
                        $value = json_decode($config->value);
                        $data = false;
                        foreach ($value as $row) {
                            if ($row->value == 1 && $row->select == 1) {
                                $data = true;
                            }
                        }
                        break;

                    case 'text':
                    case 'email':
                    case 'textarea':
                        $data = $config->value;
                        break;
                }

                $result[$config->field] = $data;
            }
        }

        return (object) $result;
    }

    /**
     *
     * @return bolean
     *
    */
    public function isNotDeleteOrder()
    {
        $config = $this->getConfigKernel('_cf_kernel_text_not_enable_delete_orders');
        if (!empty($config)) {
            $listSubdomain = explode(',', $config);
            $subdomainName = $this->_get_subdomainName();
            if (in_array($subdomainName, $listSubdomain)) {
                return true;
            }

            return false;
        }

        return false;
    }

    public function online_number_display($number, $options = null)
    {
        $class = (isset($options['class'])) ? ' class="'. $options['class'] .'"' : '';
        $html = '<span'. $class .'>';
        for ($i = 0; $i < strlen($number); $i++) {
            $val = substr($number, $i, 1);
            $html .= '<b>'. $val .'</b>';
        }
        $html .= '</span>';

        return $html;
    }

    public function getLayoutTemplate(&$subdomain)
    {
        $id = $subdomain->id;
        $setting = Setting::findFirstBySubdomainId($id);
        $layout_id = $setting->layout_id;
        $layoutConfig = LayoutConfig::findFirst([
            "conditions" => "subdomain_id = ". $id ." AND layout_id = $layout_id"
        ]);
        switch ($layout_id) {
            case 1:
                $layout = 'demo01';
                break;

            case 2:
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

            case 3:
                 $layout = 'demo03';
                break;

            case 4:
                 $layout = 'demo04';
                break;
        }

        return compact('layout_id', 'layout');
    }

    public function sw_human_time_diff($date)
    {
        $langs = array('giây', 'phút', 'giờ', 'ngày', 'tuần', 'tháng', 'năm');
        $chunks = array(
            array( 60 * 60 * 24 * 365 ,  $langs[6], $langs[6] ),
            array( 60 * 60 * 24 * 30 ,$langs[5], $langs[5] ),
            array( 60 * 60 * 24 * 7, $langs[4],$langs[4] ),
            array( 60 * 60 * 24 , $langs[3],$langs[3] ),
            array( 60 * 60 , $langs[2], $langs[2] ),
            array( 60 , $langs[1],$langs[1] ),
            array( 1,  $langs[0],$langs[0] )
        );

        $newer_date = time();


        $since = $newer_date - $date;
        //if ( 0 > $since )
        //return __( 'Gần đây', 'swhtd' );
        for ($i = 0, $j = count($chunks); $i < $j; $i++) {
            $seconds = $chunks[$i][0];
            if (($count = floor($since / $seconds)) != 0) {
                break;
            }
        }
        $output = (1 == $count) ? '1 '. $chunks[$i][1] : $count . ' ' . $chunks[$i][2];
        if (!(int)trim($output)) {
            $output = '0 ' .  $langs[0];
        }
        $output .= ' trước';
        return $output;
    }

    public function recurse_copy($src, $dst)
    {
        if (is_dir($src)) {
            $dir = opendir($src);
            @mkdir($dst);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    if (is_dir($src . '/' . $file)) {
                        $this->recurse_copy($src . '/' . $file, $dst . '/' . $file);
                    } else {
                        copy($src . '/' . $file, $dst . '/' . $file);
                    }
                }
            }
            closedir($dir);
        }
    }

    public function convertToDateType($day)
    {
        $date = str_replace('/', '-', $day);
        return date('Y-m-d', strtotime($date));
    }


    protected function _get_subdomainName()
    {
        $identity = $this->session->get('subdomain-child');
        return $identity['subdomain_name'];
    }

    /**
     * connect redis
     * @return object Redis Class
     */
    protected function redisConnect()
    {
        $redis = new \Redis();
        $redis->pconnect(getenv('REDIS_HOST'), getenv('REDIS_PORT'));
        $redis->select(getenv('REDIS_TABLE'));

        return $redis;
    }

    /**
     * Get haskey value
     * 
     * @param string $key
     * @param string $hasKey
     * @param array $options default null
     * 
     * @return object $resulst
     */
    protected function _getHasKeyValue($key, $hasKey, $options = null)
    {
        $redis = $this->redisConnect();
        $results = null;
        if ($redis->hExists($key, $hasKey)) {
            $cacheValue = $redis->hGet($key, $hasKey);
            if ($cacheValue != null) {
                if (isset($options['type']) && $options['type'] == 'array') {
                    $results = json_decode($cacheValue, true);
                } elseif ($this->isJSON($cacheValue)) {
                    $results = json_decode($cacheValue);
                } else {
                    $results = $cacheValue;
                }
            }
        }

        return $results;
    }

    /**
     * Set haskey value
     * 
     * @param string $key
     * @param string $hasKey
     * @param mixed $object
     * 
     * @return bolean
     */
    protected function _setHasKeyValue($key, $hasKey, $object, $options = null)
    {
        $redis = $this->redisConnect();
        $result = false;
        if ((is_object($object) || is_array($object))) {
            $data = null;
            if (is_object($object) && $object) {
                if (isset($options) && $options['to_array'] == false) {
                    $data = json_encode($object, JSON_UNESCAPED_UNICODE);
                } else {
                    if (!empty($object->toArray())) {
                        $data = json_encode($object->toArray(), JSON_UNESCAPED_UNICODE);
                    }
                }
            } elseif (is_array($object) && count($object) > 0) {
                $data = json_encode($object, JSON_UNESCAPED_UNICODE);
            }
            
            if ($data !== null) {
                $result = $redis->hSet($key, $hasKey, $data);
            }
        } else if ($object != null && !is_object($object) && !is_array($object)) {
            $result = $redis->hSet($key, $hasKey, $object);
        }

        // If the key has no ttl set expired for key
        if ($redis->ttl($key) == -1) {
            $redis->expire($key, getenv('CACHE_LIFETIME'));
        }
        
        return $result;
    }

    /**
     * Check json format string
     *
     * @param string $string
     * @return bolean
     */
    public function isJSON($string){
       return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}
