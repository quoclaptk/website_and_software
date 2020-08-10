<?php namespace Modules\Models;

use Phalcon\Mvc\Model;

class Product extends Model
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
    public $language_id;

    /**
     *
     * @var integer
     */
    public $depend_id;

    /**
     *
     * @var string
     */
    public $row_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $slug;

    /**
     *
     * @var string
     */
    public $link;

    /**
     *
     * @var string
     */
    public $cart_link;

    /**
     *
     * @var integer
     */
    public $enable_link;

    /**
     *
     * @var string
     */
    public $summary;

    /**
     *
     * @var string
     */
    public $code;

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
    public $in_stock;

    /**
     *
     * @var string
     */
    public $folder;

    /**
     *
     * @var string
     */
    public $photo;

    /**
     *
     * @var string
     */
    public $photo_secondary;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $keywords;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $created_at;

    /**
     *
     * @var string
     */
    public $modified_in;

    /**
     *
     * @var integer
     */
    public $hits;

    /**
     *
     * @var integer
     */
    public $purchase_number;

    /**
     *
     * @var integer
     */
    public $sort;

    /**
     *
     * @var string
     */
    public $home;

    /**
     *
     * @var string
     */
    public $hot;

    /**
     *
     * @var string
     */
    public $selling;

    /**
     *
     * @var string
     */
    public $promotion;

    /**
     *
     * @var string
     */
    public $new;

    /**
     *
     * @var string
     */
    public $gift;

    /**
     *
     * @var string
     */
    public $out_stock;

    /**
     *
     * @var string
     */
    public $active;

    /**
     *
     * @var string
     */
    public $deleted;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        /*$this->hasManyToMany(
            "id",
            "\Modules\Models\TmpProductCategory",
            "product_id", "category_id",
            "Category",
            "id",
            array(
                'alias' => 'category'
            )
        );*/
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product[]|Product|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Product|\Phalcon\Mvc\Model\ResultInterface
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
        $this->modified_in = date('Y-m-d H:i:s');
    }

    public function beforeValidationOnCreate()
    {
        $this->hits = 0;
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
            $this->getDI()->get('redis_service')->_deleteHasKey('product');
            $this->getDI()->get('redis_service')->_deleteHasKey('repository');
        }
    }
}
