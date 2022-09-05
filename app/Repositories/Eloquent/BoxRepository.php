<?php

namespace App\Repositories\Eloquent;

use App\Models\Box;
use App\Repositories\BoxRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class BoxRepository extends BaseRepository implements BoxRepositoryInterface
{

    /**
     * BoxRepository constructor.
     *
     * @param Box $model
     */
    public function __construct(Box $model)
    {
        parent::__construct($model);
    }

    /**
     * Retorna listado de cajas de un usuario. Valida que el usuario no sea rol administrador.
     * 
     * @return Collection
     */
    public function all(): Collection
    {
        $user = Auth::user();
        $user_roles = $user->roles->flatten()->pluck('name');
        $query = $this->model->with('user');

        if (!$user_roles->contains('superadmin') && !$user_roles->contains('admin')) {
            $query->whereUser($user->id);
        }

        return $query->orderBy('date', 'DESC')->get();
    }

    public function allQuery()
    {
        $user = Auth::user();
        $user_roles = $user->roles->flatten()->pluck('name');
        $query = $this->model->with('user');

        if (!$user_roles->contains('superadmin') && !$user_roles->contains('admin')) {
            $query->whereUser($user->id);
        }

        return $query->orderBy('date', 'DESC');
    }

    /**
     * Retorna caja que este abierta de un usuario
     * 
     * @param $user_id = Authenticated user
     * @return model
     */
    public function getOpenByUserId($user_id): ?Model
    {
        return $this->model->where([
            ['user_id', $user_id],
            ['closed', 0]
        ])
        ->first();
    }
}