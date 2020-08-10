<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class TmpLayoutModule extends BaseModel
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $subdomain_id;

    /**
     *
     * @var integer
     */
    public $layout_id;

    /**
     *
     * @var integer
     */
    public $module_item_id;

    /**
     *
     * @var integer
     */
    public $position_id;

    /**
     *
     * @var string
     */
    public $css;

    /**
     *
     * @var integer
     */
    public $sort;

    /**
     *
     * @var string
     */
    public $active;

    /**
     *
     * @var string
     */
    public $active_inner;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        /*$this->belongsTo(
            "layout_id",
            "Modules\Models\Layout",
            "id",
            array(
                "foreignKey" => array(
                    "alias" => "layout",
                    "message" => "The layout_id does not exist on the Layout model"
                )
            )
        );

        $this->belongsTo(
            "module_item_id",
            "Modules\Models\ModuleItem",
            "id",
            array(
                "foreignKey" => array(
                    "alias" => "module_item",
                    "message" => "The module_item_id does not exist on the Layout model"
                )
            )
        );*/
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpLayoutModule[]|TmpLayoutModule|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpLayoutModule|\Phalcon\Mvc\Model\ResultInterface
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
        $sql   = "DELETE FROM tmp_layout_module WHERE $conditions";

        // Base model
        $tmp = new TmpLayoutModule();

        // Execute the query
        return $tmp->getReadConnection()->query($sql, $params);
    }
}
