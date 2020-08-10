<?php

namespace Modules\PhalconVn;

use Phalcon\Paginator\Adapter\QueryBuilder;

class PaginationService extends BaseService
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Pagination with query builder
     * 
     * @param  mixed  $builder
     * @param  integer $limit  
     * @param  integer $page   
     * 
     * @return mixed          
     */
    public function queryBuilder($builder, $limit, $page = 1)
    {
        $paginator = new QueryBuilder(
            [
                "builder" => $builder,
                "limit"   => $limit,
                "page"    => $page,
            ]
        );

        return $paginator->getPaginate();
    }
}
