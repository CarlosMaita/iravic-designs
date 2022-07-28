<?php

namespace App\Repositories\Eloquent;

use App\User;
use Illuminate\Support\Collection;
use App\Repositories\UserRepositoryInterface;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    /**
     * UserRepository constructor.
     *
     * @param User $model
     */
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->orderBy('name')->get();
    }

    /**
     * Retorna listado de todos los empleados
     * 
     * @return 
     */
    public function allEmployeesQuery()
    {
        return $this->model
                    ->select('users.*')
                    ->join('role_user', 'role_user.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.is_employee', 1)
                    ->where('roles.is_superadmin', 0)
                    ->orderBy('users.name');
    }

    /**
     * Retorna listado de todos los empleados
     * 
     * @return Collection
     */
    public function allEmployees(): Collection
    {
        return $this->model
                    ->select('users.*')
                    ->join('role_user', 'role_user.user_id', '=', 'users.id')
                    ->join('roles', 'roles.id', '=', 'role_user.role_id')
                    ->where('roles.is_employee', 1)
                    ->where('roles.is_superadmin', 0)
                    ->orderBy('users.name')
                    ->get();
    }

    /**
     * Crea o actualiza (y elimina el deleted_at) un usuario
     * Retorna el usuario creado/actualizado.
     * 
     * @param array $attributes
     * @return Model
     */
    public function updateOrCreateByEmail(array $attributes): Object
    {
        return $this->model->withTrashed()->updateOrCreate(
                    ['email' => $attributes['email']],
                    $attributes
                );
    }
}