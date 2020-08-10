<?php

namespace Modules\Repositories;

use Modules\Models\Product;

class ProductRepository extends BaseRepository
{
	/**
	 * @var
	 */
    protected $model;

    /**
     * contruct for ProductRepository
     * @param Product $model
     */
    public function __construct(Product $model)
    {
        $this->model = $model;
    }
}
