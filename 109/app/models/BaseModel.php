<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\DI\FactoryDefault;

class BaseModel extends Model
{
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
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
            $this->getDI()->get('redis_service')->_deleteHasKey('general');
            $this->getDI()->get('redis_service')->_deleteHasKey('news');
            $this->getDI()->get('redis_service')->_deleteHasKey('product');
            $this->getDI()->get('redis_service')->_deleteHasKey('repository');
        }
    }
}
