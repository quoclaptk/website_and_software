<?php namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Validator\Uniqueness;

class Category extends Model
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
    public $parent_id;

    /**
     *
     * @var string
     */
    public $row_id;

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
    public $slug;

    /**
     *
     * @var string
     */
    public $banner;

    /**
     *
     * @var string
     */
    public $banner_md_sole;

    /**
     *
     * @var string
     */
    public $icon;

    /**
     *
     * @var string
     */
    public $font_class;

    /**
     *
     * @var integer
     */
    public $icon_type;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $hits;

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
     * @var integer
     */
    public $sort;

    /**
     *
     * @var integer
     */
    public $sort_home;

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
    public $hot;

    /**
     *
     * @var string
     */
    public $menu;

    /**
     *
     * @var string
     */
    public $show_home;

    /**
     *
     * @var string
     */
    public $list;

    /**
     *
     * @var string
     */
    public $picture;

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

        /*$this->hasMany("id", "\Modules\Models\TmpProductCategory", "category_id",  array(
            'alias' => 'tmp_product_category',
            'foreignKey' => array(
                "message" => "Danh mục này không thể xóa vì có chứa sản phẩm."
            )
        ));*/
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category[]|Category|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Category|\Phalcon\Mvc\Model\ResultInterface
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
        $this->hits = 0;

        /*Model::setup(array(
            'notNullValidations' => false
        ));*/
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
            $this->getDI()->get('redis_service')->_deleteHasKey('general');
            $this->getDI()->get('redis_service')->_deleteHasKey('repository');
        }
    }
}
