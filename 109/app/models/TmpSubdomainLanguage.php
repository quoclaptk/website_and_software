<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class TmpSubdomainLanguage extends Model
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
    public $language_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo(
            "language_id",
            "Modules\Models\Languages",
            "id",
            array(
                'alias' => 'language',
                "foreignKey" => array(
                    "message" => "The language_id does not exist on the Languages model"
                )
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpSubdomainLanguage[]|TmpSubdomainLanguage|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpSubdomainLanguage|\Phalcon\Mvc\Model\ResultInterface
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
        $sql   = "DELETE FROM tmp_subdomain_language WHERE $conditions";

        // Base model
        $tmp = new TmpSubdomainUser();

        // Execute the query
        return $tmp->getReadConnection()->query($sql, $params);
    }
}
