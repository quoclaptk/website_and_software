<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class Orders extends Model
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
    public $member_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $comment;

    /**
     *
     * @var string
     */
    public $ip_address;

    /**
     *
     * @var string
     */
    public $user_agent;

    /**
     *
     * @var string
     */
    public $order_info;

    /**
     *
     * @var string
     */
    public $code;

    /**
     *
     * @var string
     */
    public $currency;

    /**
     *
     * @var integer
     */
    public $total;

    /**
     *
     * @var integer
     */
    public $order_status;

    /**
     *
     * @var string
     */
    public $note;

    /**
     *
     * @var integer
     */
    public $payment_method;

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

        /*$this->belongsTo(
            "member_id",
            "Modules\Models\Member",
            "id",
            array(
                'alias' => 'member',
                'allowNulls' => true,
                "foreignKey" => array(
                    "message" => "The member_id does not exist on the Orders model"
                )
            )
        );*/

        $this->belongsTo(
            "order_status",
            "Modules\Models\OrderStatus",
            "id",
            array(
                'alias' => 'status',
                "foreignKey" => array(
                    "message" => "The order_status does not exist on the Orders model"
                )
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Orders[]|Orders|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Orders|\Phalcon\Mvc\Model\ResultInterface
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
