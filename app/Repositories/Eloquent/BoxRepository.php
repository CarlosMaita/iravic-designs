<?php

namespace App\Repositories\Eloquent;

use App\Models\Box;
use App\Repositories\BoxRepositoryInterface;
use Carbon\Carbon;
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

    /**
     * Counts the number of boxes per month based on the given criteria.
     *
     * @param bool $closed Whether to count closed boxes or open boxes.
     * @param int $months The number of months to go back from the current month. Default is 0.
     * @return int The count of boxes per month.
     */
    public function countBoxes_perMonths( $closed , $months = 0){
        $user = Auth::user();
        $user_roles = $user->roles->flatten()->pluck('name');
        $query = $this->model->with('user');
        if (!$user_roles->contains('superadmin') && !$user_roles->contains('admin')) {
            $query->whereUser($user->id);
        }
        // date filter
        $start  = Carbon::now()->subMonth($months)->startOfMonth();
        $end  = Carbon::now()->endOfMonth();
        $query->whereBetween('date', [$start, $end]);
        if( $closed ){
            $query->where('closed', 1);
        }else{
            $query->where('closed', 0);
        }
        return $query->count();
    }

    /**
     * Counts the number of boxes on specific dates based on the given criteria.
     *
     * @param bool $closed Whether to count closed boxes (1) or open boxes (0).
     * @param string $date_initial The initial date in the format 'd/m/Y'.
     * @param string $date_final The final date in the format 'd/m/Y'.
     * @return int The count of boxes on the specified dates.
     */
    public function countBoxes_OnDates( $closed, $date_initial, $date_final){
        $user = Auth::user();
        $user_roles = $user->roles->flatten()->pluck('name');
        $query = $this->model->with('user');
        if (!$user_roles->contains('superadmin') && !$user_roles->contains('admin')) {
            $query->whereUser($user->id);
        }
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfDay();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfDay();
        $query = $this->model->with('user');
        $query->whereBetween('date', [$start, $end]);
        if( $closed ){
            $query->where('closed', 1);
        }else{
            $query->where('closed', 0);
        }
        return $query->count();
    }

    /**
     * Counts the number of boxes per month based on the given criteria.
     *
     * @param bool $closed Whether to count closed boxes (1) or open boxes (0).
     * @param string $date_initial The initial date in the format 'd/m/Y'.
     * @param string $date_final The final date in the format 'd/m/Y'.
     * @return int The count of boxes per month.
     */
    public function countBoxes_OnMonths( $closed, $date_initial, $date_final){
        $user = Auth::user();
        $user_roles = $user->roles->flatten()->pluck('name');
        $query = $this->model->with('user');
        if (!$user_roles->contains('superadmin') && !$user_roles->contains('admin')) {
            $query->whereUser($user->id);
        }
        $start = Carbon::createFromFormat('d/m/Y', $date_initial)->startOfMonth();
        $end = Carbon::createFromFormat('d/m/Y', $date_final)->endOfMonth();
        $query = $this->model->with('user');
        $query->whereBetween('date', [$start, $end]);
        if( $closed ){
            $query->where('closed', 1);
        }else{
            $query->where('closed', 0);
        }
        return $query->count();
    }

    

}