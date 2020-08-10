<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

/**
 * Modules\Models\Profiles
 *
 * All the users registered in the application
 */
class Profiles extends Model
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
    public $active;

    /**
     *
     * @var integer
     */
    public $sort;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->hasMany('id', 'Modules\Models\Users', 'profilesId', array(
            'alias' => 'users',
            'foreignKey' => array(
                'message' => 'Profile cannot be deleted because it\'s used on Users'
            )
        ));

        $this->hasMany('id', 'Modules\Models\Permissions', 'profilesId', array(
            'alias' => 'permissions'
        ));
        //test relation
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Profiles[]|Profiles|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Profiles|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
}
