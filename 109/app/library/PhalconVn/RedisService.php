<?php

namespace Modules\PhalconVn;

use Phalcon\Cache\Backend\File as BackFile;
use Phalcon\Cache\Frontend\Data as FrontData;
// use Phalcon\Cache\Backend\Libmemcached;
use Modules\PhalconVn\General;
use Modules\Models\Subdomain;

class RedisService extends BaseService
{
    protected $redisCache;

    /**
     * Construct redis service
     */
    public function __construct()
    {
        parent::__construct();
        $redis = new \Redis();
        $redis->pconnect(getenv('REDIS_HOST'), getenv('REDIS_PORT'));
        $redis->select(getenv('REDIS_TABLE'));
        // $redis->setOption(\Redis::OPT_PREFIX, 'sub'. $this->_subdomain_id .':');
        $this->redisCache = $redis;
    }

    /**
     * Check exist cache key
     *
     * @param string $key
     * @return bolean
     */
    public function _exist($key)
    {
        return $this->redisCache->exists($key);
    }

    /**
     * Get value cache key
     *
     * @param string $key
     * @return mixed
     */
    public function _get($key)
    {
        return $this->redisCache->get($key);
    }

    /**
     * Set value cache key
     *
     * @param string $key
     * @param string $value
     * @return bolean
     */
    public function _set($key, $value)
    {
        return $this->redisCache->set($key, $value);
    }

    /**
     * Check hExists hasKey in a key
     *
     * @param string $key
     * @param string $hasKey
     * 
     * @return bolean
     */
    public function _hExists($key, $hasKey)
    {
        return $this->redisCache->hExists($key, $hasKey);
    }

    public function _hSet($key, $hasKey, $value)
    {
        $this->redisCache->hSet($key, $hasKey, $value);
    }

    public function _hGet($key, $hasKey)
    {
        $this->redisCache->hGet($key, $hasKey);
    }

    /**
     * delete hashkey subdomain
     * 
     * @param  string $subKey  [description]
     * @param  null|array $options
     * 
     * @return bolean
     */
    public function _deleteHasKey($subKey, $options = null)
    {
        if (isset($options) && $options['subdomain_id']) {
            $subdomain = Subdomain::findFirstById($options['subdomain_id']);
            if ($subdomain) {
                $key = $subKey . ':' . $options['subdomain_id'];
                $keySubName = $subKey . 's:' . $subdomain->name;
                $this->redis->del($key);
                $this->redis->del($keySubName);
            }
        } else {
            $identity = $this->session->get('auth-identity');
            $identityChild = $this->session->get('subdomain-child');
            if (!empty($identity)) {
                if (!empty($identityChild)) {
                    $subdomainChildId = $identityChild['subdomain_id'];
                    $subdomainChildName = $identityChild['subdomain_name'];
                    $key = $subKey . ':' . $subdomainChildId;
                    $keySubName = $subKey . 's:' . $subdomainChildName;
                    $this->redis->del($key);
                    $this->redis->del($keySubName);

                    if ($identity['subdomain_id'] != $identityChild['subdomain_id']) {
                        $subdomainId = $identity['subdomain_id'];
                        $subdomainName = $identity['subdomain_name'];
                        $key = $subKey . ':' . $subdomainId;
                        $keySubName = $subKey . 's:' . $subdomainName;
                        $this->redis->del($key);
                        $this->redis->del($keySubName);
                    }
                }
            }
        }
    }

    /**
     * Delete all cache key
     *
     * @return bolean
     */
    public function _deleteAll()
    {
        $identity = $this->session->get('auth-identity');
        $identityChild = $this->session->get('subdomain-child');
        if (!empty($identity)) {
            if (!empty($identityChild)) {
                $subdomainChildId = $identityChild['subdomain_id'];
                $redisSubchilds = $this->redis->keys('*sub'. $subdomainChildId .':*');
                if (!empty($redisSubchilds)) {
                    $this->redis->del($redisSubchilds);
                }

                if ($identity['subdomain_id'] != $identityChild['subdomain_id']) {
                    $subdomainId = $identity['subdomain_id'];
                    $redisSubs = $this->redis->keys('*sub'. $subdomainId .':*');
                    if (!empty($redisSubs)) {
                        $this->redis->del($redisSubs);
                    }
                }
            }
        }
    }

    /**
     * hmset hotline key
     * 
     * @param  string  $key
     * @param  array  $dates 
     * @param  array   $hotline
     * @param  integer $step 
     * 
     * @return bolean
     */
    public function hmsetKeyHotline($hotlineKey, $dates, $hotlines = [], $step = 4)
    {
        if (count($hotlines) > 1) {
            // chunk all array / 4
            foreach (array_chunk($dates, $step) as $key => $value) {
                // loop 4 days get one hotline
                for ($i = 0; $i < $step; $i++) { 
                    $hotline[] = $hotlines[$key % count($hotlines)];
                }
            }
            
            // remove array value null and sort key again
            $hotline = array_values(array_filter($hotline));

            $dateHotlines = [];
            foreach ($dates as $key => $date) {
                if (isset($hotline[$key])) {
                    $dateHotlines[$date] = $hotline[$key];
                }
            }

            // save date phone value to redis
            $this->redis->del($hotlineKey);
            return $this->redis->hmset($hotlineKey, $dateHotlines);
        }
    }

    /**
     * hmset hotline key
     * 
     * @param  string  $key
     * @param  array  $dates 
     * @param  array   $hotline
     * @param  integer $step 
     * 
     * @return bolean
     */
    public function hmsetKeyHotlineByHours ($hotlineKey, $dates, $hotlines = [], $step = 24) 
    {
        if (count($hotlines) > 1) {
            // remove array value null and sort key again
            $hotlines = array_values(array_filter($hotlines));
            // count hours with days
            $countHours = count($dates) * $step;
            $num = 0; 
            // check count 
            while($num < $countHours){
                // clone key by date
                $hotline[] = $hotlines[($num++ % count($hotlines))]; 
            }

            // chunk array with 24 elements    
            $chunkedArray = array_chunk($hotline, $step);
            // combine days with each array chunk
            $arrayCombineDates = array_combine($dates, $chunkedArray);

            $dateHotlines = [];
            foreach($arrayCombineDates as $key => $values) {
                foreach($values as $index => $value){
                    $result[date("H", strtotime($index.":00"))] = $value;
                }
                
                $dateHotlines[$key] = json_encode($result);
            }
            // save date phone value to redis
            $this->redis->del($hotlineKey);
            
            return $this->redis->hmset($hotlineKey, $dateHotlines);
        } else {
            $this->redis->del($hotlineKey);
        }
    }

    /**
     * get hotline auto generate each day
     * 
     * @param  string $type
     * @return string
     */
    public function getHotlineAutoWithDay($type = 'hotline')
    {
        $hotline = null;
        $key = $type . ':' . $this->_subdomain_id;
        if ($this->redis->exists($key)) {
            $hasKey = date('Y-m-d');
            $hotline = $this->redis->hGet($key, $hasKey);
        }

        return $hotline;
    }

    /**
     * get hotline auto generate each day
     * 
     * @param  string $type
     * @return string
     */
    public function getHotlineAuto($type = 'hotline')
    {
        $hotline = null;
        $key = $type . ':' . $this->_subdomain_id;
        if ($this->redis->exists($key)) {
            $hasKey = date('Y-m-d');
            $hotline = $this->redis->hGet($key, $hasKey);
            $getKey = json_decode($hotline, true);
            $getHour = date('H');
            $hotline = $getKey[$getHour];
        }

        return $hotline;
    }
}
