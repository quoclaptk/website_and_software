<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class ConfigItem extends BaseModel
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
    public $config_group_id;

    /**
     *
     * @var integer
     */
    public $config_core_id;

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
     * @var string
     */
    public $value_actual;

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

        $this->belongsTo(
            "config_core_id",
            "\Modules\Models\ConfigCore",
            "id",
            array(
                'alias' => 'config_core',
                "foreignKey" => array(
                    "message" => "The config_group_id does not exist on the Config Item model"
                )
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConfigItem[]|ConfigItem|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ConfigItem|\Phalcon\Mvc\Model\ResultInterface
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
