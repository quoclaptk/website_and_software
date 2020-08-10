<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\DI\FactoryDefault;

class CacheableModel extends Model
{
    /**
     * Implement a method that returns a string key based
     * on the query parameters
     */
    protected static function _createKey($parameters)
    {
        $uniqueKey = [];
        $cacheKey = '';

        if (is_array($parameters)) {
            foreach ($parameters as $key => $value) {
                if (is_scalar($value)) {
                    $uniqueKey[] = $key . ':' . $value;
                } elseif (is_array($value)) {
                    $uniqueKey[] = $key . ':[' . self::_createKey($value) . ']';
                }
            }

            $cacheKey = join(',', $uniqueKey);
        }
        

        return $cacheKey;
    }


    public static function find($parameters = null)
    {
        $results = parent::find($parameters);

        return $results;
    }

    public static function findFirst($parameters = null)
    {
        $results = parent::findFirst($parameters);

        return $results;
    }

    public function afterSave()
    {
        $this->_deleteCache();
    }

    public function afterDelete()
    {
        $this->_deleteCache();
    }

    protected function _deleteCache()
    {   
        if ($this->getDI()->get('redis_service')) {
            $this->getDI()->get('redis_service')->_deleteHasKey('subdomain');
        }
    }
}
