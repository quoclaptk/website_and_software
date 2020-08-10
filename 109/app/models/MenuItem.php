<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class MenuItem extends Model
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
     * @var integer
     */
    public $menu_id;

    /**
     *
     * @var integer
     */
    public $parent_id;

    /**
     *
     * @var integer
     */
    public $level;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $module_name;

    /**
     *
     * @var integer
     */
    public $module_id;

    /**
     *
     * @var string
     */
    public $url;

    /**
     *
     * @var string
     */
    public $other_url;

    /**
     *
     * @var string
     */
    public $title_attribute;

    /**
     *
     * @var string
     */
    public $font_class;

    /**
     *
     * @var string
     */
    public $photo;

    /**
     *
     * @var integer
     */
    public $icon_type;

    /**
     *
     * @var string
     */
    public $new_blank;

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
        $this->belongsTo(
            "menu_id",
            "Modules\Models\Menu",
            "id",
            array(
                "foreignKey" => array(
                    "message" => "The menu_id does not exist on the Menu model"
                )
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return MenuItem[]|MenuItem|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return MenuItem|\Phalcon\Mvc\Model\ResultInterface
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
            $this->getDI()->get('redis_service')->_deleteHasKey('news');
            $this->getDI()->get('redis_service')->_deleteHasKey('product');
            $this->getDI()->get('redis_service')->_deleteHasKey('general');
            $this->getDI()->get('redis_service')->_deleteHasKey('repository');
        }
    }
}
