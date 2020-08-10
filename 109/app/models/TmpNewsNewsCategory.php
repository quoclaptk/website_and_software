<?php

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class TmpNewsNewsCategory extends Model
{
     /**
     *
     * @var integer
     */
    public $subdomain_id;

    /**
     *
     * @var integer
     */
    public $news_id;

    /**
     *
     * @var integer
     */
    public $news_category_id;

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->belongsTo(
            "news_id",
            "Modules\Models\News",
            "id",
            array(
                'alias' => 'news',
                "foreignKey" => array(
                    "message" => "The news_id does not exist on the News model"
                )
            )
        );
    }

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpNewsNewsCategory[]|TmpNewsNewsCategory|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpNewsNewsCategory|\Phalcon\Mvc\Model\ResultInterface
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

    public static function deleteByRawSql($conditions, $params = null)
    {
        // A raw SQL statement
        $sql   = "DELETE FROM tmp_news_news_category WHERE $conditions";

        // Base model
        $tmp_news_tags = new TmpNewsNewsCategory();

        // Execute the query
        return $tmp_news_tags->getReadConnection()->query($sql, $params);
    }
}
