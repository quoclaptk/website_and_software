<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class TmpLandingModule extends Model
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
    public $landing_page_id;

    /**
     *
     * @var integer
     */
    public $module_item_id;

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
     * Initialize method for model.
     */
    public function initialize()
    {
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpLandingModule[]|TmpLandingModule|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpLandingModule|\Phalcon\Mvc\Model\ResultInterface
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
        $sql   = "DELETE FROM tmp_landing_module WHERE $conditions";

        // Base model
        $tmp = new TmpLandingModule();

        // Execute the query
        return $tmp->getReadConnection()->query($sql, $params);
    }
}
