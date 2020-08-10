<?php

namespace Modules\Repositories;

use Modules\Models\Category;

class CategoryRepository extends BaseRepository
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}
