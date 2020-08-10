<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class LayoutConfig extends Model
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
    public $layout_id;

    /**
     *
     * @var string
     */
    public $main_color;

    /**
     *
     * @var string
     */
    public $main_text_color;

    /**
     *
     * @var string
     */
    public $css;

    /**
     *
     * @var integer
     */
    public $sort;

    /**
     *
     * @var integer
     */
    public $enable_color;

    /**
     *
     * @var integer
     */
    public $enable_css;

    /**
     *
     * @var string
     */
    public $hide_header;

    /**
     *
     * @var string
     */
    public $hide_left;

    /**
     *
     * @var string
     */
    public $hide_right;

    /**
     *
     * @var string
     */
    public $hide_footer;

    /**
     *
     * @var string
     */
    public $show_left_inner;

    /**
     *
     * @var string
     */
    public $show_right_inner;

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
        $this->belongsTo(
            "layout_id",
            "Modules\Models\Layout",
            "id",
            array(
                "foreignKey" => array(
                    "message" => "The layout_id does not exist on the BannerType model"
                )
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return LayoutConfig[]|LayoutConfig|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return LayoutConfig|\Phalcon\Mvc\Model\ResultInterface
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
