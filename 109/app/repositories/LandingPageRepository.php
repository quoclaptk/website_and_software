<?php

namespace Modules\Repositories;

use Modules\Models\LandingPage;

class LandingPageRepository extends BaseRepository
{
    protected $model;

    public function __construct(LandingPage $model)
    {
        $this->model = $model;
    }
}
