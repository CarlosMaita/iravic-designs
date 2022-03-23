<?php

namespace App\Repositories\Eloquent;

use App\Models\Schedule;
use App\Repositories\ScheduleRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

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
     * Retorna listado de agendas
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        $user = Auth::user();
        $user_roles = $user->roles->flatten()->pluck('name');
        $query = $this->model->orderBy('date', 'DESC');

        if (!$user_roles->contains('superadmin') && !$user_roles->contains('admin')) {
            $query->whereHasVisitasByResponsable($user->id);
        }

        return $query->get();
    }

    /**
     * Crea o "actualiza", una agenda para una fecha si esta no existe.
     */
    public function firstOrCreate($attributes = null): Model
    {
        return $this->model->firstOrCreate($attributes);
    }
}