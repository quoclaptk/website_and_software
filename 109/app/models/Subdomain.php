<?php 

namespace Modules\Models;

use Phalcon\Mvc\Model;

class Subdomain extends Model
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
    public $create_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var integer
     */
    public $folder_sort;

    /**
     *
     * @var string
     */
    public $folder;

    /**
     *
     * @var string
     */
    public $note;

    /**
     *
     * @var integer
     */
    public $share_price;

    /**
     *
     * @var string
     */
    public $copyright_name;

    /**
     *
     * @var string
     */
    public $copyright_link;

    /**
     *
     * @var integer
     */
    public $sort;

    /**
     *
     * @var string
     */
    public $active_date;

    /**
     *
     * @var string
     */
    public $expired_date;

    /**
     *
     * @var string
     */
    public $active;

    /**
     *
     * @var string
     */
    public $hot;

    /**
     *
     * @var string
     */
    public $suspended;

    /**
     *
     * @var string
     */
    public $closed;

    /**
     *
     * @var string
     */
    public $new;

    /**
     *
     * @var string
     */
    public $special;

    /**
     *
     * @var string
     */
    public $add_to_server;

    /**
     *
     * @var string
     */
    public $not_thumb;

    /**
     *
     * @var string
     */
    public $display;

    /**
     *
     * @var string
     */
    public $duplicate;

    /**
     *
     * @var string
     */
    public $copyright;

    /**
     *
     * @var string
     */
    public $other_interface;

    /**
     *
     * @var string
     */
    public $is_ssl;

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
        /*$this->belongsTo(
            "create_id",
            "Modules\Models\Users",
            "id",
            array(
                'alias' => 'createUser',
                "foreignKey" => array(
                    "message" => "The create_id does not exist on the User model"
                )
            )
        );*/

        $this->hasMany("id", "Modules\Models\HtmlBanner", "subdomain_id", array(
            'alias' => 'htmlBanner'
        ));

        $this->hasMany("id", "Modules\Models\Orders", "subdomain_id", array(
            'alias' => 'orders'
        ));

        $this->hasMany("id", "Modules\Models\Contact", "subdomain_id", array(
            'alias' => 'contact'
        ));

        $this->hasMany("id", "Modules\Models\Newsletter", "subdomain_id", array(
            'alias' => 'newsletter'
        ));

        $this->hasMany("id", "Modules\Models\CustomerMessage", "subdomain_id", array(
            'alias' => 'customerMessage'
        ));

        $this->hasMany("id", "Modules\Models\FormItem", "subdomain_id", array(
            'alias' => 'formItem'
        ));

        $this->hasMany("id", "Modules\Models\SubdomainRating", "subdomain_id", array(
            'alias' => 'subdomainRating'
        ));

        $this->hasMany("id", "Modules\Models\MenuItem", "subdomain_id", array(
            'alias' => 'menuItem'
        ));

        $this->hasMany("id", "Modules\Models\Category", "subdomain_id", array(
            'alias' => 'category'
        ));

        $this->hasMany("id", "Modules\Models\NewsMenu", "subdomain_id", array(
            'alias' => 'newsMenu'
        ));

        $this->hasMany("id", "Modules\Models\Setting", "subdomain_id", array(
            'alias' => 'setting'
        ));

        $this->hasMany("id", "Modules\Models\Domain", "subdomain_id", array(
            'alias' => 'domain'
        ));

        // get setting lang vi
        $this->hasOne("id", "Modules\Models\Setting", "subdomain_id", array(
            'alias' => 'settingVi',
            'params'   => [
                'conditions' => "language_id = 1"
            ]
        ));
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Subdomain[]|Subdomain|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Subdomain|\Phalcon\Mvc\Model\ResultInterface
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

    public function afterSave()
    {
        $this->_deleteCache();
    }

    public function afterDelete()
    {
        $this->_deleteCache();
    }

    protected function _deleteCache()
    {   
        if ($this->getDI()->get('redis_service')) {
            $this->getDI()->get('redis_service')->_deleteHasKey('subdomain');
        }
    }
}
