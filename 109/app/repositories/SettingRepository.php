<?php

namespace Modules\Repositories;

use Modules\Models\Setting;

class SettingRepository extends BaseRepository
{
    protected $model;

    public function __construct(Setting $model)
    {
        $this->model = $model;
    }
}
