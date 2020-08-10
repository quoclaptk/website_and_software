<?php

namespace Modules\PhalconVn;

use Modules\Models\ConfigItem;
use Modules\Helpers\FormHelper;

class ConfigService extends BaseService
{
    /**
     * @var cache key $cacheKey
     */
    protected $cacheKey;

    public function __construct()
    {
        parent::__construct();
        $this->cacheKey = 'general:' . $this->_subdomain_id;
    }

    /**
     * get all config of subdomain
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
                        $result[$config->field] = ($this->redis_service->getHotlineAutoWithDay($config->field) !== null) ? $this->redis_service->getHotlineAutoWithDay($config->field) : $data;
                    }
                }
            }

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $result, ['lang' => false]);
        }

        return $result;
    }

    /**
     * get config item subdomain login
     * @param  int $subdomainId
     * @return array
     */
    public function getConfigItemSubdomain($subdomainId)
    {
        $hasKey = __FUNCTION__ . '_' . $subdomainId;
        $result = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array', 'lang' => false]);
        if ($result === null) {
            $configs = ConfigItem::find([
                "columns" => "id, config_group_id, name, field, value, type",
                "conditions" => "subdomain_id = ". $subdomainId ." AND active = 'Y' AND deleted = 'N'"
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
                            $res = 0;
                            $value = json_decode($config->value);
                            if (!empty($value)) {
                                foreach ($value as $row) {
                                    if ($row->select == 1) {
                                        $res = $row->value;
                                    }
                                }
                            }
                            
                            $data = $res;
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

            $this->_setHasKeyValue($this->cacheKey, $hasKey, $result, ['lang' => false]);
        }

        return $result;
    }

    /**
     * Get config detail with type
     *
     * @param string $field
     * @return bolean|string|mixed
     */
    public function getConfigItemDetail($field)
    {
        // get data form cache
        $hasKey = 'getConfigItem';
        $result = $this->_getHasKeyValue($this->cacheKey, $hasKey, ['type' => 'array', 'lang' => false]);
        if ($result !== null && isset($result[$field])) {
            return $result[$field];
        }

        $configItem = ConfigItem::findFirst(["columns" => "id, config_group_id name, field, value, type", "conditions" => "subdomain_id = " . $this->_subdomain_id . " AND field = '$field' AND active = 'Y' AND deleted = 'N'"]);
        if ($configItem) {
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
                case 'email':
                case 'textarea':
                    return $configItem->value;
                break;
            }
        }

        return false;
    }

    public function getLanguagesTranslate()
    {
        $configLanguage = $this->getConfigItemDetail('_cf_checkbox_select_language');
        $languages = [];

        if (!empty($configLanguage)) {
            $formHelper = new FormHelper();
            $languages = $formHelper->languagesGoogleTranslate();
            foreach ($languages as $key => $value) {
                if (!in_array($value, $configLanguage)) {
                    unset($languages[$key]);
                }
            }
        }

        return $languages;
    }
}
