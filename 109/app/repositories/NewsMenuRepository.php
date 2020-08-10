<?php

namespace Modules\Repositories;

use Modules\Models\NewsMenu;

class NewsMenuRepository extends BaseRepository
{
    protected $model;

    public function __construct(NewsMenu $model)
    {
        $this->model = $model;
    }
}
