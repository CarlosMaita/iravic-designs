<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface ScheduleRepositoryInterface
{
    public function firstOrCreate($attributes = null): Model;
}