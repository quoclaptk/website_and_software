<?php 

namespace Modules\Models;

use Modules\Models\Behavior\Imageable;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Banner extends Model
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
    public $name;

    /**
     *
     * @var string
     */
    public $link;

    /**
     *
     * @var string
     */
    public $photo;

    /**
     *
     * @var integer
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $sort;

    /**
     *
     * @var string
     */
    public $left_ads;

    /**
     *
     * @var string
     */
    public $right_ads;

    /**
     *
     * @var string
     */
    public $md_banner_2;

    /**
     *
     * @var string
     */
    public $md_banner_3;

    /**
     *
     * @var string
     */
    public $vertical_slider;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasManyToMany(
            "id",
            "\Modules\Models\TmpBannerBannerType",
            "banner_id",
            "banner_type_id",
            "Banner Type",
            "id",
            array(
                'alias' => 'banner'
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Banner[]|Banner|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Banner|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function beforeCreate()
    {
        // Set the creation date
        $this->created_at = date('Y-m-d H:i:s');
    }

    public function beforeUpdate()
    {
        // Set the modification date
        $this->modified_in = date('Y-m-d H:i:s');
    }

    public function beforeValidationOnCreate()
    {
        // $this->photo = 'N';
        //$this->thumb = 'N';
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
            $this->getDI()->get('redis_service')->_deleteHasKey('general');
        }
    }
}
