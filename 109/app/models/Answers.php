<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class Answers extends Model
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
    public $usually_question_id;

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
    public $question;

    /**
     *
     * @var string
     */
    public $answer;

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
    public $created_at;

    /**
     *
     * @var string
     */
    public $modified_in;

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
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Answers[]|Answers|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Answers|\Phalcon\Mvc\Model\ResultInterface
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
        $this->modified_in = date('Y-m-d H:i:s');
    }

    public function beforeValidationOnCreate()
    {
    }
}
