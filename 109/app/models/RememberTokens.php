<?php 

namespace Modules\Models;

use Phalcon\Mvc\Model;

/**
 * RememberTokens
 *
 * Stores the remember me tokens
 */
class RememberTokens extends Model
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
    public $usersId;

    /**
     *
     * @var string
     */
    public $token;

    /**
     *
     * @var string
     */
    public $userAgent;

    /**
     *
     * @var integer
     */
    public $createdAt;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo('usersId', 'Modules\Models\Users', 'id', array(
            'alias' => 'user'
        ));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return RememberTokens[]|RememberTokens|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return RememberTokens|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Before create the user assign a password
     */
    public function beforeValidationOnCreate()
    {
        //Timestamp the confirmaton
        $this->createdAt = time();
    }
}
