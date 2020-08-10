<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class ConfigCore extends Model
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
    public $config_group_id;

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
    public $field;

    /**
     *
     * @var string
     */
    public $value;

    /**
     *
     * @var integer
     */
    public $min_value;

    /**
     *
     * @var integer
     */
    public $max_value;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $guide;

    /**
     *
     * @var string
     */
    public $place_holder;

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
            "config_group_id",
            "\Modules\Models\ConfigGroup",
            "id",
            array(
                'alias' => 'config_group',
                "foreignKey" => array(
                    "message" => "The config_group_id does not exist on the Config Group model"
                )
            )
        );

        $this->hasMany("id", "\Modules\Models\ConfigItem", "config_core_id", array(
            'alias' => 'config_item',
            'foreignKey' => array(
                "message" => "Không thể xóa vì chứa ràng buộc dữ liệu"
            )
        ));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConfigCore[]|ConfigCore|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConfigCore|\Phalcon\Mvc\Model\ResultInterface
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
