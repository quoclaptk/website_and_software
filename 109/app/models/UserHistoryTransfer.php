<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class UserHistoryTransfer extends Model
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
    public $user_id;

    /**
     *
     * @var integer
     */
    public $online_payment_type;

    /**
     *
     * @var string
     */
    public $code;

    /**
     *
     * @var integer
     */
    public $amount;

    /**
     *
     * @var integer
     */
    public $payment_id;

    /**
     *
     * @var string
     */
    public $payment_type;

    /**
     *
     * @var string
     */
    public $error_text;

    /**
     *
     * @var string
     */
    public $secure_code;

    /**
     *
     * @var string
     */
    public $token_nl;

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
            "user_id",
            "Modules\Models\Users",
            "id",
            array(
                'alias' => 'user',
                "foreignKey" => array(
                    "message" => "The user_id does not exist on the Users model"
                )
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserHistoryTransfer[]|UserHistoryTransfer|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return UserHistoryTransfer|\Phalcon\Mvc\Model\ResultInterface
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
