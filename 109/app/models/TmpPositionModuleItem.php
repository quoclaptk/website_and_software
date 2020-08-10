<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class TmpPositionModuleItem extends Model
{
    /**
     *
     * @var integer
     */
    public $subdomain_id;

    /**
     *
     * @var integer
     */
    public $position_id;

    /**
     *
     * @var integer
     */
    public $module_item_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo(
            "module_item_id",
            "Modules\Models\ModuleItem",
            "id",
            array(
                'alias' => 'module_item',
                "foreignKey" => array(
                    "message" => "The module_item_id does not exist on the ModuleItem model"
                )
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpPositionModuleItem[]|TmpPositionModuleItem|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpPositionModuleItem|\Phalcon\Mvc\Model\ResultInterface
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
        $sql   = "DELETE FROM tmp_position_module_item WHERE $conditions";

        // Base model
        $tmp = new TmpPositionModuleItem();

        // Execute the query
        return $tmp->getReadConnection()->query($sql, $params);
    }
}
