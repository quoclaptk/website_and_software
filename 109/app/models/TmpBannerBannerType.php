<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class TmpBannerBannerType extends Model
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
    public $banner_id;

    /**
     *
     * @var integer
     */
    public $banner_type_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo(
            "banner_id",
            "Modules\Models\Banner",
            "id",
            array(
                'alias' => 'banner',
                "foreignKey" => array(
                    "message" => "The product_detail_id does not exist on the Banner model"
                )
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpBannerBannerType[]|TmpBannerBannerType|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpBannerBannerType|\Phalcon\Mvc\Model\ResultInterface
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
        $sql   = "DELETE FROM tmp_banner_banner_type WHERE $conditions";

        // Base model
        $tmp = new TmpBannerBannerType();

        // Execute the query
        return $tmp->getReadConnection()->query($sql, $params);
    }
}
