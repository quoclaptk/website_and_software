<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class TmpProductProductElementDetail extends Model
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
    public $product_id;

    /**
     *
     * @var integer
     */
    public $product_element_detail_id;

    /**
     *
     * @var string
     */
    public $combo_id;

    /**
     *
     * @var integer
     */
    public $cost;

    /**
     *
     * @var integer
     */
    public $price;

    /**
     *
     * @var integer
     */
    public $cost_usd;

    /**
     *
     * @var integer
     */
    public $price_usd;

    /**
     *
     * @var integer
     */
    public $selected;

    /**
     *
     * @var string
     */
    public $photo;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo(
            "product_id",
            "Modules\Models\Product",
            "id",
            array(
                'alias' => 'product',
                "foreignKey" => array(
                    "message" => "The product_id does not exist on the Product model"
                )
            )
        );

        /*$this->belongsTo(
            "product_element_detail_id",
            "Modules\Models\ProductElementDetail",
            "id",
            array(
                'alias' => 'product_element_detail',
                "foreignKey" => array(
                    "message" => "The product_element_detail_id does not exist on the ProductElementDetail model"
                )
            )
        );*/
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpProductProductElementDetail[]|TmpProductProductElementDetail|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpProductProductElementDetail|\Phalcon\Mvc\Model\ResultInterface
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
        $sql   = "DELETE FROM tmp_product_product_element_detail WHERE $conditions";

        // Base model
        $tmp_news_tags = new TmpProductProductElementDetail();

        // Execute the query
        return $tmp_news_tags->getReadConnection()->query($sql, $params);
    }
}
