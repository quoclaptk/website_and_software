<?php

namespace Modules\PhalconVn;

use Phalcon\Mvc\User\Component;
use Modules\Models\Languages;
use Phalcon\DI\FactoryDefault;

class BaseService extends Component
{
    /**
     * @var integer
     */
    protected $_lang_id;

    /**
     * @var string
     */
    protected $_lang_code;
    
    /**
     * @var integer
     */
    protected $_subdomain_id;

    /**
     * Constructor BaseService|Phalcon\Mvc\User\Component
     */
    public function __construct()
    {
        $languageCode = ($this->dispatcher->getParam("language")) ? $this->dispatcher->getParam("language") : 'vi';
        $languageInfo = Languages::findFirstByCode($languageCode);
        $this->_lang_id = $languageInfo->id;
        $this->_lang_code = $languageInfo->code;
        $this->_subdomain_id = $this->mainGlobal->getDomainId();
        $this->_folder = $this->mainGlobal->getDomainFolder();
    }

    /**
     * Return id language
     * @return int
     */
    public function getLangId()
    {
        return $this->_lang_id;
    }

    /**
     * Return code language
     * @return string
     */
    public function getLangCode()
    {
        return $this->_lang_code;
    }

    /**
     * Return Subdomain Id
     * @return string
     */
    public function getSubId()
    {
        return $this->_subdomain_id;
    }

    /**
     * Return Subdomain Folder
     * @return string
     */
    public function getSubFolder()
    {
        return $this->_folder;
    }

    /**
     * Get value cache key
     *
     * @param string $key
     * @return mixed
     */
    protected function _existCache($key)
    {
        return $this->redis_service->_exist($key);
    }

    /**
     * Get value cache key
     *
     * @param string $key
     * @return mixed
     */
    protected function _getCache($key)
    {
        $result = null;
        if ($this->_existCache($key)) {
            $result = $this->redis_service->_get($key);
        }

        return $result;
    }

    /**
     * Set value cache key
     *
     * @param string $key
     * @param string $value
     * @return bolean
     */
    public function _setCache($key, $value)
    {
        return $this->redis_service->_set($key, $value);
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
    public function _getHasKeyValue($key, $hasKey, $options = null)
    {
    	$hasKey = (isset($options) && isset($options['lang']) && $options['lang'] == false) ? $hasKey : $hasKey . '_'. $this->_lang_code;
        $results = null;
        if ($this->redis->hExists($key, $hasKey)) {
            $cacheValue = $this->redis->hGet($key, $hasKey);
            if ($cacheValue != null) {
                if (isset($options['type']) && $options['type'] == 'array') {
                    $results = json_decode($cacheValue, true);
                } elseif ($this->general->isJSON($cacheValue)) {
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
    public function _setHasKeyValue($key, $hasKey, $object, $options = null)
    {
    	$hasKey = (isset($options) && isset($options['lang']) && $options['lang'] == false) ? $hasKey : $hasKey . '_'. $this->_lang_code;
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
                $result = $this->redis->hSet($key, $hasKey, $data);
            }
        } else if ($object != null && !is_object($object) && !is_array($object)) {
            $result = $this->redis->hSet($key, $hasKey, $object);
        }

        // If the key has no ttl set expired for key
        if ($this->redis->ttl($key) == -1) {
            $this->redis->expire($key, getenv('CACHE_LIFETIME'));
        }
        
        return $result;
    }
}
