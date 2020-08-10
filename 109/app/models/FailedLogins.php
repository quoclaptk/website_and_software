<?php 

namespace Modules\Models;

use Phalcon\Mvc\Model;

/**
 * FailedLogins
 *
 * This model registers unsuccessfull logins registered and non-registered users have made
 */
class FailedLogins extends Model
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
    public $ipAddress;

    /**
     *
     * @var integer
     */
    public $attempted;

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
     * @return FailedLogins[]|FailedLogins|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return FailedLogins|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
