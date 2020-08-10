<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class TmpModuleGroupLayout extends Model
{

    /**
     *
     * @var integer
     */
    public $module_group_id;

    /**
     *
     * @var integer
     */
    public $layout_id;

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
     * @return TmpModuleGroupLayout[]|TmpModuleGroupLayout|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpModuleGroupLayout|\Phalcon\Mvc\Model\ResultInterface
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
        $sql   = "DELETE FROM tmp_module_group_layout WHERE $conditions";

        // Base model
        $tmp = new TmpModuleGroupLayout();

        // Execute the query
        return $tmp->getReadConnection()->query($sql, $params);
    }
}
