<?php 

namespace Modules\Models;

use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Resultset\Simple as Resultset;

class TmpNewsTags extends Model
{

    /**
     *
     * @var integer
     */
    public $news_id;

    /**
     *
     * @var integer
     */
    public $tag_id;

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
     * @return TmpNewsTags[]|TmpNewsTags|\Phalcon\Mvc\Model\ResultSetInterface
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return TmpNewsTags|\Phalcon\Mvc\Model\ResultInterface
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
        $sql   = "DELETE FROM tmp_news_tags WHERE $conditions";

        // Base model
        $tmp_news_tags = new TmpNewsTags();

        // Execute the query
        return $tmp_news_tags->getReadConnection()->query($sql, $params);
    }
}
