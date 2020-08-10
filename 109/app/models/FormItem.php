<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class FormItem extends Model
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
    public $form_group_id;

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
    public $place_arrive;

    /**
     *
     * @var string
     */
    public $place_pic;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var string
     */
    public $day;

    /**
     *
     * @var string
     */
    public $hour;

    /**
     *
     * @var string
     */
    public $start_time;

    /**
     *
     * @var string
     */
    public $end_time;

    /**
     *
     * @var string
     */
    public $class;

    /**
     *
     * @var string
     */
    public $subjects;

    /**
     *
     * @var string
     */
    public $studen_number;

    /**
     *
     * @var string
     */
    public $learning_level;

    /**
     *
     * @var string
     */
    public $learning_time;

    /**
     *
     * @var string
     */
    public $learning_day;

    /**
     *
     * @var string
     */
    public $request;

    /**
     *
     * @var string
     */
    public $teacher_code;

    /**
     *
     * @var string
     */
    public $minute;

    /**
     *
     * @var string
     */
    public $method;

    /**
     *
     * @var integer
     */
    public $number_ticket;

    /**
     *
     * @var string
     */
    public $file;

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
            "form_group_id",
            "Modules\Models\FormGroup",
            "id",
            array(
                'alias' => 'form_group',
                "foreignKey" => array(
                    "message" => "The form_group_id does not exist on the Form Group model"
                )
            )
        );

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
     * @return FormItem[]|FormItem|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FormItem|\Phalcon\Mvc\Model\ResultInterface
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
