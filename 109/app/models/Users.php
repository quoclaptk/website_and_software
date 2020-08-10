<?php 

namespace Modules\Models;

use Phalcon\Mvc\Model;

class Users extends Model
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
    public $username;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $password;

    /**
     *
     * @var string
     */
    public $mustChangePassword;

    /**
     *
     * @var integer
     */
    public $profilesId;

    /**
     *
     * @var integer
     */
    public $role;

    /**
     *
     * @var string
     */
    public $fullName;

    /**
     *
     * @var string
     */
    public $sex;

    /**
     *
     * @var string
     */
    public $birthday;

    /**
     *
     * @var string
     */
    public $creditCard;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $facebook;

    /**
     *
     * @var integer
     */
    public $balance;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $cityRegion;

    /**
     *
     * @var string
     */
    public $token;

    /**
     *
     * @var string
     */
    public $signup;

    /**
     *
     * @var string
     */
    public $banned;

    /**
     *
     * @var string
     */
    public $suspended;

    /**
     *
     * @var string
     */
    public $deleted;

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

        $this->belongsTo(
            'profilesId',
            'Modules\Models\Profiles',
            'id',
            array(
                'alias' => 'profile',
                'reusable' => true)
        );

        $this->belongsTo(
            'profilesId',
            'Modules\Models\Profiles',
            'id',
            array(
                'alias' => 'profile',
                'reusable' => true)
        );

        $this->hasMany('id', 'Modules\Models\SuccessLogins', 'usersId', array(
            'alias' => 'successLogins',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));

        $this->hasMany('id', 'Modules\Models\PasswordChanges', 'usersId', array(
            'alias' => 'passwordChanges',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));

        $this->hasMany('id', 'Modules\Models\ResetPasswords', 'usersId', array(
            'alias' => 'resetPasswords',
            'foreignKey' => array(
                'message' => 'User cannot be deleted because he/she has activity in the system'
            )
        ));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users[]|Users|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Users|\Phalcon\Mvc\Model\ResultInterface
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
        /*if (empty($this->password)) {

            //Generate a plain temporary password
            //$tempPassword = preg_replace('/[^a-zA-Z0-9]/', '', base64_encode(openssl_random_pseudo_bytes(12)));
            $tempPassword = NULL;
            //The user must change its password in first login
            $this->mustChangePassword = 'Y';

            //Use this password as default
            //$this->password = $this->getDI()->getSecurity()->hash($tempPassword);
            //pass:123456
            //$password_123 = '$2a$08$DOhUA9dordhMPXp8ujwgseuNSvz/4E3Pk.n7aYn8WgWiROZRv7fCi';
            //$this->password = $password_123;

        } else {
            //The user must not change its password in first login
            $this->mustChangePassword = 'N';

        }

        //The account must be confirmed via e-mail
        $this->active = 'Y';

        //The account is not suspended by default
        $this->suspended = 'N';

        //The account is not banned by default
        $this->banned = 'N';

        $this->fullName = 'N';
        $this->sex = 'C';
        $this->birthday = 'N';

        $this->creditCard = 'N';
        $this->phone = 'N';
        $this->cityRegion = 'N';
        $this->deleted = 'N';*/
    }

    /**
     * Send a confirmation e-mail to the user if the account is not active
     */
    public function afterSave()
    {
        /*if ($this->active == 'N') {

            $emailConfirmation = new EmailConfirmations();

            $emailConfirmation->usersId = $this->id;
            if ($emailConfirmation->save()) {
                $this->getDI()->getFlash()->notice(
                    'A confirmation mail has been sent to ' . $this->email
                );
            }
        }*/
    }
    
}
