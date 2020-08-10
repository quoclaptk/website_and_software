<?php

namespace Modules\Repositories;

use MicheleAngioni\PhalconRepositories\AbstractRepository;
use Modules\PhalconVn\MainGlobal;
use Modules\PhalconVn\BaseService;

class BaseRepository extends AbstractRepository
{
    /**
     * Allows to query a set of records that match the specified conditions
     * 
     * @param mixed $parameters
     * 
     * @return \Phalcon\Mvc\Model\ResultSetInterface
     */
    public function findByParams($parameters = null)
    {
        return $this->model->find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return \Phalcon\Mvc\Model\ResultInterface
     */
    public function findFirstByParams($parameters = null)
    {
        return $this->model->findFirst($parameters);
    }

    /**
     * findByPropertyName Model
     * 
     * @param  string $propertyName 
     * @param  mixed $propertyValue
     * 
     * @return mixed               
     */
    public function findByPropertyName($propertyName, $propertyValue)
    {
        $functionName = 'findBy' . $propertyName;

        return $this->model->$functionName ($propertyValue);
    }

    /**
     * findFirstByPropertyName Model
     * 
     * @param  string $propertyName 
     * @param  mixed $propertyValue
     * 
     * @return mixed               
     */
    public function findFirstByPropertyName($propertyName, $propertyValue)
    {
        $functionName = 'findBy' . $propertyName;

        return $this->model->$functionName ($propertyValue);
    }

    /**
     * ORM Save
     * 
     * @return Phalcon\Mvc\Model::save() 
     */
    public function save()
    {
        return $this->model->save();
    }

    /**
     * ORM Query Critical
     * 
     * @return Phalcon\Mvc\Model\Criteria 
     */
    public function query()
    {
        return $this->model::query();
    }



    /**
     * get item detail admin
     * 
     * @param  integer  $id   
     * @param  boolean $lang 
     * 
     * @return mixed     
     */
    public function getItemUpdateDetail($id, $lang = true)
    {
        $conditions = "subdomain_id = ".$this->_get_subdomainID()." AND id = $id";
        if ($lang == true) {
            $langId = LANGDEFAULTID;
            $conditions .= " AND language_id = $langId";
        }

        $item = $this->model->findFirst([
            "conditions" => $conditions
        ]);

        return $item;
    }

	/**
	 * get slug for route
     * 
	 * @param  interger $subdomainId
     * 
	 * @return mixed            
	 */
	public function getSlugForRoute($langId)
	{
		$key = $this->getCacheKey();
		$hasKey = get_class($this) . '_' . __FUNCTION__ . '_' . $langId;
		$items = $this->_getHasKeyValue($key, $hasKey);
		if ($items === null) {
			$items = $this->model->find([
			    "columns" => "slug",
			    "conditions" => "subdomain_id = ". $this->getDomainId() ." AND language_id = $langId AND active='Y' AND deleted = 'N'"
			]);

			$this->_setHasKeyValue($key, $hasKey, $items);
		}
		

		return $items;
	}

    /**
     * get slug for route
     * 
     * @param  interger $langId
     * 
     * @return mixed            
     */
    public function getItemByLangId($langId)
    {
        $key = $this->getCacheKey();
        $hasKey = get_class($this) . '_' . __FUNCTION__ . '_' . $langId;
        $item = $this->_getHasKeyValue($key, $hasKey);
        if ($item === null) {
            $item = $this->model->findFirst([
                'conditions' => 'subdomain_id = '. $this->getDomainId() .' AND language_id = '. $langId .''
            ]);

            $this->_setHasKeyValue($key, $hasKey, $item);
        }

        return $item;
    }

	/**
	 * connect redis
     * 
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
	 * get subdomain id
     * 
	 * @return interger
	 */
	protected function getDomainId()
	{
		$mainGlobal = MainGlobal::getInstance();

		return $mainGlobal->getDomainId();
	}

    protected function _get_subdomainID()
    {
        $mainGlobal = MainGlobal::getInstance();

        return $mainGlobal->getDomainIdAdmin();
    }

	/**
	 * get cache key
     * 
	 * @return string
	 */
	protected function getCacheKey()
	{
		$cacheKey = 'repository:' . $this->getDomainId();

		return $cacheKey;
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
     * 
     * @return bolean
     */
    public function isJSON($string){
       return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }
}
