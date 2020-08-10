<?php

namespace Modules\PhalconVn;

use Phalcon\Mvc\User\Component;
use Modules\Models\Banner;
use Modules\Models\BannerType;
use Modules\Models\BannerHtml;
use Modules\Models\Setting;
use Modules\Models\ModuleItem;

class BannerService extends BaseService
{
    /**
     * @var $cacheKey
     */
    protected $cacheKey;

    public function __construct()
    {
        parent::__construct();
        $this->cacheKey = 'general:' . $this->_subdomain_id;
    }

    /**
     * Get banner type info
     * 
     * @param integer $bannerTypeId
     * 
     * @return mixed
     */
    public function getBannerTypeInfo($bannerTypeId)
    {
        $hasKey = __FUNCTION__ . '_' . $bannerTypeId;
        $banner = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($banner === null) {
            $moduleItemCurrent = ModuleItem::findFirstById($bannerTypeId);
            if ($moduleItemCurrent->subdomain_id != $this->_subdomain_id) {
                $moduleItem = ModuleItem::findFirst([
                    "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND type = '". $moduleItemCurrent->type ."' AND name = '". $moduleItemCurrent->name ."' AND active = 'Y' AND deleted = 'N'"
                ]);
                $moduleItemId = $moduleItem->id;
            } else {
                $moduleItemId = $bannerTypeId;
            }

            $banner = BannerType::findFirst([
                "columns" => "id, name, slider, partner, type",
                "conditions" => "module_item_id = $moduleItemId AND active = 'Y' AND deleted = 'N'"
            ]);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $banner);
        }
        
        return $banner;
    }

    /**
     * Get banner with Type
     * 
     * @param integer $bannerTypeId
     * 
     * @return mixed
     */
    public function getListBanner($bannerTypeId)
    {
        $hasKey = __FUNCTION__ . '_' . $bannerTypeId;
        $banners = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($banners === null) {
            $banners = Banner::query()
                ->columns(["id", "name", "link", "photo"])
                ->join("Modules\Models\TmpBannerBannerType", "tmp.banner_id = Modules\Models\Banner.id", "tmp")
                ->where("Modules\Models\Banner.subdomain_id = ". $this->_subdomain_id ." AND banner_type_id = $bannerTypeId AND language_id = $this->_lang_id AND active = 'Y' AND deleted = 'N'")
                ->limit(10)
                ->orderBy("sort ASC, id DESC")
                ->groupBy("id")
                ->execute();

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $banners);
        }
            
        return $banners;
    }

    public function getBannerHtml()
    {
        $setting = Setting::findFirstBySubdomainId($this->_subdomain_id);
        $item =  BannerHtml::findFirst([
            "conditions" => "subdomain_id = ". $this->_subdomain_id ." AND id = ". $setting->banner_html_id ." AND active = 'Y' AND deleted = 'N'"
        ]);

        return $item;
    }

    /**
     * Get list banner asd
     * 
     * @param  string $type
     * 
     * @return mixed      
     */
    public function getBannerAdsType($type)
    {
        $hasKey = __FUNCTION__ . '_' . $type;
        $banners = $this->_getHasKeyValue($this->cacheKey, $hasKey);
        if ($banners === null) {
            $banners =  Banner::find([
                'columns' => 'id, name, link, photo',
                'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND language_id = '. $this->_lang_id .' AND '. $type .' = "Y" AND active = "Y" AND deleted = "N"',
                'order' => 'sort ASC, id DESC'
            ]);

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $banners);
        }
        
        return $banners;
    }

    /**
     * Get Banner in a Type
     * 
     * @param integer $type
     * 
     * @return mixed
     */
    public function getBannerInType($type = 1)
    {
        $bannerType = BannerType::findFirst([
            'columns' => 'id',
            'conditions' => 'subdomain_id = '. $this->_subdomain_id .' AND type = '. $type .' AND active = "Y" AND deleted = "N"'
        ]);

        $banners = null;
        if ($bannerType) {
            $banners = $this->getListBanner($bannerType->id);
        }
        
        return $banners;
    }
}
