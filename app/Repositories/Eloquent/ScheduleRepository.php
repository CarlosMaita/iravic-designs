<?php

namespace App\Repositories\Eloquent;

use App\Models\Schedule;
use App\Repositories\ScheduleRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ScheduleRepository extends BaseRepository implements ScheduleRepositoryInterface
{

    /**
     * ScheduleRepository constructor.
     *
     * @param Schedule $model
     */
    public function __construct(Schedule $model)
    {
        parent::__construct($model);
    }

    /**
     * 
     */
    public function firstOrCreate($attributes = null): Model
    {
        return $this->model->firstOrCreate($attributes);
    }
}