<?php 

namespace Modules\Models;

use Phalcon\Mvc\Model;

class News extends Model
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
    public $type_id;

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
    public $row_id;

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
    public $slogan;

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
    public $folder;

    /**
     *
     * @var string
     */
    public $photo;

    /**
     *
     * @var string
     */
    public $thumb;

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
     * @var string
     */
    public $head_content;

    /**
     *
     * @var string
     */
    public $body_content;

    /**
     *
     * @var integer
     */
    public $hits;

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
    public $hot;

    /**
     *
     * @var string
     */
    public $new;

    /**
     *
     * @var string
     */
    public $most_view;

    /**
     *
     * @var string
     */
    public $introduct;

    /**
     *
     * @var string
     */
    public $slider;

    /**
     *
     * @var string
     */
    public $hot_effect;

    /**
     *
     * @var string
     */
    public $statistical;

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
        /*$this->hasManyToMany(
            "id",
            "\Modules\Models\TmpNewsNewsCategory",
            "news_id", "news_category_id",
            "NewsCategory",
            "id",
            array(
                'alias' => 'category'
            )
        );*/
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return News[]|News|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return News|\Phalcon\Mvc\Model\ResultInterface
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
            $this->getDI()->get('redis_service')->_deleteHasKey('repository');
        }
    }
}
