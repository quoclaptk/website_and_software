<?php

namespace Modules\Repositories;

use Modules\Models\News;

class NewsRepository extends BaseRepository
{
    protected $model;

    public function __construct(News $model)
    {
        $this->model = $model;
    }
}
