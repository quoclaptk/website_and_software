<?php 

namespace Modules\Models;

use Phalcon\Mvc\Model;

class NewsMenu extends Model
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
    public $summary;

    /**
     *
     * @var string
     */
    public $content;

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
    public $hits;

    /**
     *
     * @var string
     */
    public $font_class;

    /**
     *
     * @var string
     */
    public $icon;

    /**
     *
     * @var integer
     */
    public $icon_type;

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
    public $menu;

    /**
     *
     * @var string
     */
    public $footer;

    /**
     *
     * @var string
     */
    public $home;

    /**
     *
     * @var string
     */
    public $policy;

    /**
     *
     * @var string
     */
    public $popup;

    /**
     *
     * @var string
     */
    public $reg_form;

    /**
     *
     * @var string
     */
    public $messenger_form;

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
        /*$this->hasMany("id", "\Modules\Models\TmpNewsNewsMenu", "news_menu_id",  array(
            'alias' => 'news',
            'foreignKey' => array(
                "message" => "Danh mục này không thể xóa vì có chứa bài viết."
            )
        ));*/
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return NewsMenu[]|NewsMenu|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return NewsMenu|\Phalcon\Mvc\Model\ResultInterface
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
            $this->getDI()->get('redis_service')->_deleteHasKey('news');
            $this->getDI()->get('redis_service')->_deleteHasKey('general');
            $this->getDI()->get('redis_service')->_deleteHasKey('repository');
        }
    }
}
