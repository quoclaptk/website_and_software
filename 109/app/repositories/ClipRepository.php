<?php

namespace Modules\Repositories;

use Modules\Models\Clip;

class ClipRepository extends BaseRepository
{
    protected $model;

    public function __construct(Clip $model)
    {
        $this->model = $model;
    }
}
