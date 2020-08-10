<?php 

namespace Modules\Helpers;

use Modules\PhalconVn\Tag;
use Modules\PhalconVn\MainGlobal;
use Modules\Models\ConfigItem;
use Modules\Models\WordItem;
use Modules\Models\Languages;
use Modules\Repositories\SettingRepository;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Mvc\Dispatcher;

class ProductShortcodeHelper
{
    protected $mainGlobal;

    protected $subdomain;

    protected $settingRepository;

    protected $cf;

    protected $word;

    protected $_subdomain_id;

    protected $_folder;

    protected $_lang_code;

    /**
     *
     * @var cache key $cacheKey
     */
    protected $cacheKey;

    public function __construct()
    {
        $this->_tag = new Tag();
        $this->dispatcher = new Dispatcher();
        $this->mainGlobal = MainGlobal::getInstance();;
        $this->settingRepository = new SettingRepository(new \Modules\Models\Setting());
        $this->subdomain = $this->mainGlobal->checkDomain();
        $this->_subdomain_id = $this->subdomain->id;
        $this->_folder = $this->mainGlobal->getDomainFolder();
        $languageCode = ($this->dispatcher->getParam("language")) ? $this->dispatcher->getParam("language") : 'vi';
        $this->_lang_code = $languageCode;
        $languageInfo = Languages::findFirstByCode($languageCode);
        $languageId = $languageInfo->id;
        $this->setting =  $this->settingRepository->getItemByLangId($languageId);
        $this->cacheKey = 'general:' . $this->_subdomain_id;
        $this->cf = $this->getConfigItem();
        $this->word = $this->getWordTranslation();
    }

    /**
     * list product html display short code
     * 
     * @param  mixed $product
     * @param  null|array $options
     * 
     * @return string
     */
    public function productListViewHtmlShortcode($product, $options = null)
    {
        $layout = $this->setting->layout_id;
        switch ($layout) {
            case 1:
                $product_class = 'col-md-3';
                break;

            case 2:
                $product_class = 'col-md-4';
                break;
            
            default:
                $product_class = 'col-md-4';
                break;
        }

        $subdomain = $this->subdomain;
        $cf = $this->cf;
        $word = $this->word;

        $id = (isset($options['array']) && $options['array'] == true) ? $product['id'] : $product->id;
        $name = (isset($options['array']) && $options['array'] == true) ? $product['name'] : $product->name;
        $slug = (isset($options['array']) && $options['array'] == true) ? $product['slug'] : $product->slug;
        $folder = (isset($options['array']) && $options['array'] == true) ? $product['folder'] : $product->folder;
        $photo = (isset($options['array']) && $options['array'] == true) ? $product['photo'] : $product->photo;
        $prPrice = (isset($options['array']) && $options['array'] == true) ? $product['price'] : $product->price;
        $cost = (isset($options['array']) && $options['array'] == true) ? $product['cost'] : $product->cost;
        $summary = (isset($options['array']) && $options['array'] == true) ? $product['summary'] : $product->summary;
        $link = (isset($options['array']) && $options['array'] == true) ? $product['link'] : $product->link;
        $cartLink = (isset($options['array']) && $options['array'] == true) ? $product['cart_link'] : $product->cart_link;
        $enable_link = (isset($options['array']) && $options['array'] == true) ? $product['enable_link'] : $product->enable_link;
        $name_show = $this->_tag->cut_string($name, 70);
        $outStock = (isset($options['array']) && $options['array'] == true) ? $product['out_stock'] : $product->out_stock;
        $purchaseNumber = (isset($options['array']) && $options['array'] == true) ? $product['purchase_number'] : $product->purchase_number;
        $hits = (isset($options['array']) && $options['array'] == true) ? $product['hits'] : $product->hits;
        $target = '';
        if ($cf['_cf_text_all_product_url'] != '' && filter_var($cf['_cf_text_all_product_url'], FILTER_VALIDATE_URL)) {
            $url = $cf['_cf_text_all_product_url'];
        } else {
            if ($enable_link == 1 && !empty($link) && filter_var($link, FILTER_VALIDATE_URL)) {
                $target = ' target="blank"';
                $url = $link;
            } else {
                $target = '';
                $url = ($this->_lang_code == 'vi') ? $this->site_url($slug) : $this->site_url($this->_lang_code . '/' . $slug);
            }
        }
        
        if (!empty($photo)) {
            if (file_exists("files/product/" . $subdomain->folder . "/thumb/360x360/" . $folder . "/" . $photo)) {
                $photo = "/files/product/" . $subdomain->folder . "/thumb/360x360/" . $folder . "/" . $photo;
            } elseif (file_exists("files/product/" . $subdomain->folder . "/" . $folder . "/" . $photo)) {
                $photo = "/files/product/" . $subdomain->folder . "/" . $folder . "/" . $photo;
            } else {
                $photo = "/assets/images/no-image.png";
            }
        } else {
            $photo = "/assets/images/no-image.png";
        }

        if ($cf['_turn_off_product_price'] == true) {
            $price = $this->cms_price($prPrice, $cost, "đ", ["class" => "box_product_price"]);
            $percent = $this->percen_calculate($prPrice, $cost);
        } else {
            $price = '';
            $percent = '';
        }

        $outStockHtml = $outStock == 'Y' ? '<span class="status-hot hot">'. $word->_('_het_hang') .'</span>' : '';
        $class =  (isset($options['detail']) && $options['detail'] == true) ? $product_class : $product_class . ' col-sm-4 col-xs-6 col-ss-12 col-product';
        $html = sprintf('<div class="%s"><div class="box_product"><a href="%s"%s>%s<div class="box_product_img"><img src="%s" alt="%s"></div>%s</a><div class="box_product_name"><a href="%s"%s>%s</a></div>', $class, $url, $target, $outStockHtml, $photo, $name, $percent, $url, $target, $name_show);
        if ($cf['_cf_radio_show_summary_to_product'] == true) {
            $html .= '<div class="box_product_summary">'. $summary .'</div>';
        }

        if ($price != '' || $percent != '' || $cf['_turn_off_cart_btn'] == true) {
            $html .= '<div class="box_product_price_cart clearfix">' . $price;
            if ($cf['_turn_off_cart_btn'] == true) {
                $html .= '<div class="btn_cart text-uppercase">';
                if (!empty($cartLink) && filter_var($cartLink, FILTER_VALIDATE_URL)) {
                    $html .= '<a href="'. $cartLink .'"><span class="btn btn-sm btn-warning">'. $word['_mua_ngay'] .'</span><i class="icon-giohang"></i></a>';
                } elseif ($cf['_cf_text_cart_other_url'] != '' && filter_var($cf['_cf_text_cart_other_url'], FILTER_VALIDATE_URL)) {
                    $html .= '<a href="'. $cf['_cf_text_cart_other_url'] .'"><span class="btn btn-sm btn-warning">'. $word['_mua_ngay'] .'</span><i class="icon-giohang"></i></a>';
                } else {
                    $html .= sprintf('<span class="btn btn-sm btn-warning add-to-cart" data-id="%s">%s</span><i class="icon-giohang" data-id="%s"></i>', $id, $word['_mua_ngay'], $id);
                }
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        if ($cf['_show_btn_view_product_detail'] == true) {
            $html .= sprintf('<div class="btn_view_product_detail text-center"><a href="%s" %s class="btn btn-blue bar_web_bgr">%s</a></div>', $url, $target, $word['_chi_tiet']);
        }

        if ($cf['_show_company_to_product'] == true || $cf['_show_hotline_to_product'] == true || $cf['_show_email_to_product'] == true) {
            $html .= '<div class="box_product_company_info"><ul class="text-center">';
            if ($cf['_show_company_to_product'] == true) {
                $html .= '<li class="product_company_name"><strong>'. $this->setting->name .'</strong></li>';
            }

            if ($cf['_show_hotline_to_product'] == true && $this->setting->hotline != '') {
                $html .= sprintf('<li class="product_company_hotline"><span>%s: </span><strong><a href="tel:%s">%s</a></strong></li>', $word['_hotline'], $this->setting->hotline, $this->setting->hotline);
            }

            if ($cf['_show_email_to_product'] == true && $this->setting->email != '') {
                $html .= sprintf('<li class="product_company_email"><span>%s: </span><strong><a href="mailto:%s">%s</a></strong></li>', $word['_email'], $this->setting->email, $this->setting->email);
            }

            $html .= '</ul></div>';
        }

        if ($cf['_cf_radio_show_purchase_number_to_product'] == true || $cf['_cf_radio_show_views_to_product'] == true) {
            $html .= '<div class="box_product_purchase_hits clearfix">';
            if ($cf['_cf_radio_show_purchase_number_to_product'] == true) {
                $html .= '<div class="box_product_purchase pull-left text-left"><i class="fa fa-user-o"></i> '. $word->_('_luot_mua') .': <span class="box_product_purchase_number">'. $purchaseNumber .'</span></div>';
            }

            if ($cf['_cf_radio_show_views_to_product'] == true) {
                $html .= '<div class="box_product_hits pull-left"><i class="fa fa-eye"></i> '. $word->_('_luot_xem') .': <span class="box_product_hits_number">'. $hits .'</span></div>';
            }

            $html .= '</div>';
        }

        $html .= '</div></div>';

        return $html;
    }

    /**
     * get all config of subdomain
     * 
     * @return array
     */
    public function getConfigItem()
    {
        $hasKey = __FUNCTION__;
        $result = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array', 'lang' => false]);
        if ($result === null) {
           $configs = ConfigItem::find([
                "columns" => "id, config_group_id, name, field, value, type",
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND active = 'Y' AND deleted = 'N'"
            ]);

            $result = [];
            if (count($configs) > 0) {
                foreach ($configs as $config) {
                    switch ($config->type) {
                        case 'checkbox':
                            $value = json_decode($config->value);
                            $data = [];
                            foreach ($value as $row) {
                                if ($row->value == 1 && $row->select == 1) {
                                    $data[] = $row->name;
                                }
                            }
                            
                            break;
                        case 'radio':
                            $value = json_decode($config->value);
                            $data = false;
                            if (!empty($value)) {
                                foreach ($value as $row) {
                                    if ($row->value == 1 && $row->select == 1) {
                                        $data = true;
                                    }
                                }
                            }
                            
                            break;
                        case 'select':
                            $value = json_decode($config->value);
                            if (!empty($value)) {
                                foreach ($value as $row) {
                                    if ($row->select == 1) {
                                        $data = $row->value;
                                    }
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

                    //if exist config auto set data again
                    if ($config->field == '_txt_phone_alo' || $config->field == '_cf_text_link_zalo' || $config->field == '_cf_text_hotline_number') {
                        $result[$config->field] = ($this->getHotlineAutoWithDay($config->field) !== null) ? $this->getHotlineAutoWithDay($config->field) : $data;
                    }
                }
            }
            
            $this->_setHasKeyValue($this->cacheKey, $hasKey, $result, ['lang' => false]);
        }

        return $result;
    }

    /**
     * Get word translate
     * 
     * @return Phalcon\Translate\Adapter\NativeArray
     */
    protected function getWordTranslation()
    {
        $messageFolder = __DIR__ . '/../../app/messages/';
        $langFile = $messageFolder . 'subdomains/' . $this->_folder . '/' . $this->_lang_code . '.json';
        if (file_exists($langFile)) {
            $translations = json_decode(
                file_get_contents($langFile),
                true
            );
        } else {
            $words = WordItem::find([
            "columns" => "id, name, word_key, word_translate",
                "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND active = 'Y' AND deleted = 'N'"
            ]);

            $translations = [];
            if (count($words) > 0) {
                foreach ($words as $word) {
                    $translations[$word->name] = $word->word_translate;
                }
            }
        }

        return new NativeArray(
            [
                'content' => $translations,
            ]
        );
    }

    /**
     * Display html price
     * 
     * @param  int $sale 
     * @param  int $price
     * @param  string $unit 
     * @param  null|array $option
     * 
     * @return string       
     */
    protected function cms_price($sale, $price, $unit = 'đ', $option = null)
    {
        $priceHtml = null;
        $cf =  $this->cf;
        if (isset($option['note']) && $option['note'] == true) {
            $notPice = $cf['_cf_text_note_price_product'];
            $priceNote = ($notPice != '') ? ' <span class="price-pr-detail-note">('. $notPice .')</span>' : '';
        } else {
            $priceNote = '';
        }
        
        if ($sale > 0 && $price > 0) {
            $saleFormat = number_format($sale, 0, ',', '.')  . " " . $unit;
            $priceFormat = number_format($price, 0, ',', '.') . " " . $unit;

            if (!empty($option["class"])) {
                $priceHtml = sprintf('<div class="'. $option["class"] .'">
                                <div class="product_price_old">%s%s</div>
                                <div class="product_price_new">%s</div>
                            </div>', $priceFormat, $priceNote, $saleFormat);
            } else {
                $priceHtml = sprintf(
                    '<div class="product_price_old">%s%s</div>
                            <div class="product_price_new">%s</div>',
                    $priceFormat,
                    $priceNote,
                    $saleFormat
                );
            }
        }

        if ($sale == 0 && $price > 0) {
            $priceFormat = number_format($price, 0, ',', '.') . " " . $unit;
            if (!empty($option["class"])) {
                $priceHtml = sprintf('<div class="'. $option["class"] .'">
                                    <p class="product_price_new product_price">%s%s</p>
                                </div>', $priceFormat, $priceNote);
            } else {
                $priceHtml = sprintf('<p class="product_price_new product_price">%s%s</p>', $priceFormat, $priceNote);
            }
        }

        if ($price == 0) {
            $priceFormat = 'Liên hệ';
            if (!empty($option["class"])) {
                $priceHtml = sprintf('<div class="'. $option["class"] .' product_price">
                                    <p class="product_price_new">%s</p>
                                </div>', $priceFormat);
            } else {
                $priceHtml = sprintf('<p class="product_price_new">%s</p>', $priceFormat);
            }
        }

        return $priceHtml;
    }

    /**
     * Percen calculate display html
     * 
     * @param  int $x       
     * @param  int $divisor 
     * @param  null|array $option  
     * @return null|string          
     */
    protected function percen_calculate($x, $divisor, $option = null)
    {
        $percentHtml = null;
        if (is_numeric($x) && is_numeric($divisor)) {
            if ($x > 0 && $x < $divisor) {
                $percent = ($x / $divisor) * 100;
                $percent = round($percent, 0, PHP_ROUND_HALF_UP);
                $percent = round($percent, 0);
                $percent = 100 - $percent;

                if ($percent > 0) {
                    $percentHtml = sprintf('<p class="%s">%s</p>', "price_percent", '-' . $percent . '%');
                }
            } else {
                $percentHtml = null;
            }
        }

        return $percentHtml;
    }

    /**
     * Add prefix to url
     * 
     * @param  string $link 
     * @return string       
     */
    protected function site_url($link = "")
    {
        if ($link != "") {
            $url =  "/" . $link . "/";
        } else {
            $url = "/";
        }
        return $url;
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
    protected function isJSON($string){
       return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    /**
     * get hotline auto generate each day
     * @param  string $type
     * @return string
     */
    protected function getHotlineAutoWithDay($type = 'hotline')
    {
        $redis = $this->redisConnect();
        $hotline = null;
        $key = $type . ':' . $this->_subdomain_id;
        if ($redis->exists($key)) {
            $hasKey = date('Y-m-d');
            $hotline = $redis->hGet($key, $hasKey);
        }

        return $hotline;
    }
}
