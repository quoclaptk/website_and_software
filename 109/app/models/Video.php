<?php 

namespace Modules\Models;

use Phalcon\Mvc\Model;

class Video extends Model
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
    public $category_video_id;

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
    public $file;

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
    public $created_at;

    /**
     *
     * @var integer
     */
    public $hits;

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
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo(
            "category_video_id",
            "\Modules\Models\CategoryVideo",
            "id",
            array(
                "foreignKey" => array(
                    "message" => "The category video id does not exist on the CategoryVideo model"
                )
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Video[]|Video|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Video|\Phalcon\Mvc\Model\ResultInterface
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
        // $this->photo = 'N';
        //$this->thumb = 'N';
    }
}
