<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;  
use Illuminate\Support\Collection;

interface VisitRepositoryInterface
{
    public function all($params = null): Collection;

    public function allBySchedule($schedule_id): Collection;
}