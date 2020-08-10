<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class CustomerMessage extends Model
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
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $comment;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $subject;

    /**
     *
     * @var string
     */
    public $note;

    /**
     *
     * @var string
     */
    public $box_select_option;

    /**
     *
     * @var string
     */
    public $work_province;

    /**
     *
     * @var string
     */
    public $birthday;

    /**
     *
     * @var string
     */
    public $home_town;

    /**
     *
     * @var string
     */
    public $voice;

    /**
     *
     * @var string
     */
    public $teaching_time;

    /**
     *
     * @var string
     */
    public $portrait_image;

    /**
     *
     * @var string
     */
    public $certificate_image;

    /**
     *
     * @var string
     */
    public $college_address;

    /**
     *
     * @var string
     */
    public $major;

    /**
     *
     * @var string
     */
    public $graduation_year;

    /**
     *
     * @var string
     */
    public $level;

    /**
     *
     * @var string
     */
    public $gender;

    /**
     *
     * @var string
     */
    public $forte;

    /**
     *
     * @var string
     */
    public $subjects;

    /**
     *
     * @var string
     */
    public $class;

    /**
     *
     * @var string
     */
    public $salary;

    /**
     *
     * @var string
     */
    public $other_request;

    /**
     *
     * @var string
     */
    public $created_at;

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
            "subdomain_id",
            "Modules\Models\Subdomain",
            "id",
            array(
                'alias' => 'subdomain',
                "foreignKey" => array(
                    "message" => "The subdomain_id does not exist on the Subdomain model"
                )
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return CustomerMessage[]|CustomerMessage|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return CustomerMessage|\Phalcon\Mvc\Model\ResultInterface
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
    }

    public function beforeValidationOnCreate()
    {
    }
}
