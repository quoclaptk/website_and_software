<?php namespace Modules\Models;

use Phalcon\Mvc\Model;

class UrlConfig extends Model
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
    public $link;

    /**
     *
     * @var string
     */
    public $wrapper;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $summary;

    /**
     *
     * @var string
     */
    public $img;

    /**
     *
     * @var string
     */
    public $img_link_replace;

    /**
     *
     * @var string
     */
    public $href;

    /**
     *
     * @var string
     */
    public $content;

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
    public $create_at;

    /**
     *
     * @var string
     */
    public $modified_in;

    public function initialize()
    {
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
        $this->hot = 'N';
    }
}
