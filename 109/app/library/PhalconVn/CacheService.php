<?php

namespace Modules\PhalconVn;

use Phalcon\Cache\Backend\File as BackFile;
use Phalcon\Cache\Frontend\Data as FrontData;
use Phalcon\Cache\Backend\Redis;
// use Phalcon\Cache\Backend\Libmemcached;
use Modules\PhalconVn\General;

class CacheService extends BaseService
{
    protected $cache;

    protected $redisCache;

    public function __construct()
    {
        $mainGlobal = new MainGlobal();
        $this->general = new General();
        $this->_subdomain_id = $mainGlobal->getDomainId();

        if (!is_dir("../app/caches")) {
            mkdir("../app/caches", 0777);
        }

        if (!is_dir("../app/caches/" . $this->_subdomain_id)) {
            mkdir("../app/caches/" . $this->_subdomain_id, 0777);
        }

        $type_save_cache = $mainGlobal->getConfigKernel("_type_save_cache");

        $frontCache = new FrontData([
            'lifetime' => 172800,
        ]);
        
        $this->cache = new BackFile(
            $frontCache,
            [
                'cacheDir' => '../app/caches/'.  $this->_subdomain_id .'/',
            ]
        );

        // Create the Cache setting redis connection options
        $this->redisCache = new Redis(
            $frontCache,
            [
                "host"       => "localhost",
                "port"       => 6379,
                "auth"       => "",
                "persistent" => false,
                "index"      => 0,
            ]
        );
    }

    public function get($cacheKey, $options = null)
    {
        if (isset($options['type']) && $options['type'] == 'redis') {
            $result = $this->redisCache->get($cacheKey);
        } else {
            $result = $this->cache->get($cacheKey);
        }

        return $result;
    }
    
    public function save($cacheKey, $cacheValue, $options = null)
    {
        if (isset($options['type']) && $options['type'] == 'redis') {
            $result = $this->redisCache->save($cacheKey, $cacheValue);
        } else {
            $result = $this->cache->save($cacheKey, $cacheValue);
        }

        return $result;
    }

    public function queryKeys()
    {
        $result = $this->cache->queryKeys();

        return $result;
    }

    public function queryKeysRedis($key)
    {
        $result = $this->redisCache->queryKeys($key);

        return $result;
    }

    public function delete($cacheKey, $options = null)
    {
        if (isset($options['type']) && $options['type'] == 'redis') {
            $result = $this->redisCache->delete($cacheKey);
        } else {
            $result = $this->cache->delete($cacheKey);
        }
        
        return $result;
    }

    public function deleteCacheAllSubdomain($options = null)
    {
        $this->general->rrmdir('../app/caches');
    }

    public function deleteCacheSubdomain($subdomainId, $options = null)
    {
        $this->general->deleteAllFileInFolder('../app/caches/' . $subdomainId);
    }

    public function deleteAll($options = null)
    {
        $keys = $this->cache->queryKeys();

        foreach ($keys as $key) {
            if (isset($options['type']) && $options['type'] == 'redis') {
                $this->redisCache->delete($key);
            } else {
                $this->cache->delete($key);
            }
        }
    }
}
