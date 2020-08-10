<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class TmpPositionModuleGroup extends Model
{

    /**
     *
     * @var integer
     */
    public $position_id;

    /**
     *
     * @var integer
     */
    public $module_group_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpPositionModuleGroup[]|TmpPositionModuleGroup|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpPositionModuleGroup|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function beforeCreate()
    {
    }

    public function beforeUpdate()
    {
    }

    public function beforeValidationOnCreate()
    {
    }

    public static function deleteByRawSql($conditions, $params = null)
    {
        // A raw SQL statement
        $sql   = "DELETE FROM tmp_position_module_group WHERE $conditions";

        // Base model
        $tmp_position_module_gorup = new TmpPositionModuleGroup();

        // Execute the query
        return $tmp_position_module_gorup->getReadConnection()->query($sql, $params);
    }
}
