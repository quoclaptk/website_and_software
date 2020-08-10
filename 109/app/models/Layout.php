<?php 

namespace Modules\Models;

use Phalcon\Mvc\Model;

class Layout extends Model
{

    /**
     *
     * @var integer
     */
    public $id;

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
    public $slug;

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
        $this->hasMany("id", "Modules\Models\LayoutConfig", "layout_id", array(
            'alias' => 'layout_config',
            'foreignKey' => array(
                "message" => "Không thể xóa ví có chứa ràng buộc dữ liệu."
            )
        ));
        $this->hasMany("id", "Modules\Models\TmpLayoutModule", "layout_id", array(
            'alias' => 'tmp_layout_module',
            'foreignKey' => array(
                "message" => "Không thể xóa ví có chứa ràng buộc dữ liệu."
            )
        ));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Layout[]|Layout|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Layout|\Phalcon\Mvc\Model\ResultInterface
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
        // Set the modification date
        $this->modified_in = date('Y-m-d H:i:s');
    }

    public function beforeValidationOnCreate()
    {
        $this->sort = 1;
    }
}
