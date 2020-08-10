<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;

class Setting extends BaseModel
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
    public $language_id;

    /**
     *
     * @var integer
     */
    public $depend_id;

    /**
     *
     * @var integer
     */
    public $layout_id;

    /**
     *
     * @var integer
     */
    public $layout_subdomain_id;

    /**
     *
     * @var integer
     */
    public $banner_html_id;

    /**
     *
     * @var string
     */
    public $row_id;

    /**
     *
     * @var string
     */
    public $name;

    /**
     *
     * @var string
     */
    public $website;

    /**
     *
     * @var string
     */
    public $slogan;

    /**
     *
     * @var string
     */
    public $banner_1;

    /**
     *
     * @var string
     */
    public $banner_2;

    /**
     *
     * @var string
     */
    public $banner_3;

    /**
     *
     * @var string
     */
    public $banner_4;

    /**
     *
     * @var string
     */
    public $address;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $email_order;

    /**
     *
     * @var string
     */
    public $phone;

    /**
     *
     * @var string
     */
    public $hotline;

    /**
     *
     * @var string
     */
    public $fax;

    /**
     *
     * @var string
     */
    public $tax_code;

    /**
     *
     * @var string
     */
    public $business_license;

    /**
     *
     * @var string
     */
    public $copyright;

    /**
     *
     * @var string
     */
    public $facebook;

    /**
     *
     * @var string
     */
    public $google;

    /**
     *
     * @var string
     */
    public $youtube;

    /**
     *
     * @var string
     */
    public $yahoo;

    /**
     *
     * @var string
     */
    public $zalo;

    /**
     *
     * @var string
     */
    public $twitter;

    /**
     *
     * @var string
     */
    public $image_meta;

    /**
     *
     * @var string
     */
    public $image_menu_2;

    /**
     *
     * @var integer
     */
    public $enable_image_menu_2;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $keywords;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @var string
     */
    public $article_home;

    /**
     *
     * @var string
     */
    public $contact;

    /**
     *
     * @var string
     */
    public $footer;

    /**
     *
     * @var integer
     */
    public $enable_contact_default;

    /**
     *
     * @var integer
     */
    public $enable_footer_default;

    /**
     *
     * @var integer
     */
    public $enable_form_contact;

    /**
     *
     * @var integer
     */
    public $enable_video_article_home;

    /**
     *
     * @var integer
     */
    public $enable_image_article_home;

    /**
     *
     * @var integer
     */
    public $enable_form_reg_article_home;

    /**
     *
     * @var integer
     */
    public $enable_search_advance_article_home;

    /**
     *
     * @var integer
     */
    public $enable_map;

    /**
     *
     * @var string
     */
    public $map_code;

    /**
     *
     * @var string
     */
    public $youtube_code;

    /**
     *
     * @var string
     */
    public $image_article_home;

    /**
     *
     * @var string
     */
    public $logo;

    /**
     *
     * @var string
     */
    public $text_logo;

    /**
     *
     * @var integer
     */
    public $enable_logo_text;

    /**
     *
     * @var string
     */
    public $favicon;

    /**
     *
     * @var string
     */
    public $bgr_ycbg;

    /**
     *
     * @var string
     */
    public $analytics;

    /**
     *
     * @var string
     */
    public $note_payment_method_2;

    /**
     *
     * @var string
     */
    public $head_content;

    /**
     *
     * @var string
     */
    public $body_content;

    /**
     *
     * @var string
     */
    public $order_admin_note;

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
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return Setting[]|Setting|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return Setting|\Phalcon\Mvc\Model\ResultInterface
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }
    
    public function beforeUpdate()
    {
        // Set the modification date
        $this->modified_in = date('Y-m-d H:i:s');
    }

    public function afterSave()
    {
        $this->_deleteCache();
    }

    public function afterDelete()
    {
        $this->_deleteCache();
    }
}
