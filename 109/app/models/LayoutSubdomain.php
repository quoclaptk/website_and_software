<?php namespace Modules\Models;

use Phalcon\Mvc\Model;

class LayoutSubdomain extends Model
{
    public function initialize()
    {
        $this->hasMany("id", "Modules\Models\LayoutConfig", "layout_subdomain_id", array(
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
     * @return Category[]
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category
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
