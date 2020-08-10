<?php

namespace Modules\Logger;

/**
 * Basic Array based Logging for debugging Phalcon Operations
 * @package PhalconX\Logger\Adapter
 */
class Basic extends \Phalcon\Logger\Adapter
{
    private $data = array();

    /**
     * Add a statement to the log
     * @param string $statement
     * @param null $type
     * @param array $params
     * @return $this|\Phalcon\Logger\Adapter
     */
    public function log($statement, $type=null, array $params=null)
    {
        $this->data[] = array('sql'=>$statement, 'type'=>$type, 'params'=>$params); // array('sql'=>$statement, 'type'=>$type);
        return $this;
    }

    /**
     * return the log
     * @return array
     */
    public function getLog(){
        return $this->data;
    }

    /**
     * Required function for the interface, unused
     * @param $message
     * @param $type
     * @param $time
     * @param $context
     */
    public function logInternal($message, $type, $time, $context){

    }

    /**
     * Required function for the interface, unused
     */
    public function getFormatter(){

    }

    /**
     * Required function for the interface, unused
     */
    public function close(){

    }
}