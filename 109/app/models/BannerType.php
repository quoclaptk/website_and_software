<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class BannerType extends Model
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
    public $module_item_id;

    /**
     *
     * @var string
     */
    public $name;

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
    public $sort;

    /**
     *
     * @var string
     */
    public $slider;

    /**
     *
     * @var string
     */
    public $partner;

    /**
     *
     * @var integer
     */
    public $type;

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
        $this->hasMany("id", "\Modules\Models\TmpBannerBannerType", "banner_type_id", array(
            'alias' => 'banner',
            'foreignKey' => array(
                "message" => "Không thể xóa ví có chứa ràng buộc dữ liệu."
            )
        ));
    }

   /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return BannerType[]|BannerType|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return BannerType|\Phalcon\Mvc\Model\ResultInterface
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
    }
}
