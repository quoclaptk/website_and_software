<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class ModuleItem extends BaseModel
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
    public $module_group_id;

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
    public $photo;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $type_id;

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
     *
     * @var string
     */
    public $create_at;

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
        /*$this->belongsTo(
            "module_group_id",
            "Modules\Models\ModuleGroup",
            "id",
            array(
                'alias' => 'module_group',
                "foreignKey" => array(
                    "message" => "The module_group_id does not exist on the ModuleGroup model"
                )
            )
        );

        $this->hasMany("id", "\Modules\Models\TmpPositionModuleItem", "module_item_id",  array(
            'alias' => 'tmp_position_module_item',
            'foreignKey' => array(
                "message" => "Không thể xóa vì chứa ràng buộc dữ liệu"
            )
        ));*/
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return ModuleItem[]|ModuleItem|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return ModuleItem|\Phalcon\Mvc\Model\ResultInterface
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
