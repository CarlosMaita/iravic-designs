<?php

namespace App\Repositories\Eloquent;

use App\Models\Box;
use Illuminate\Support\Collection;
use App\Repositories\BoxRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BoxRepository extends BaseRepository implements BoxRepositoryInterface
{

    /**
     * BrandRepository constructor.
     *
     * @param Brand $model
     */
    public function __construct(Box $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->with('user')->orderBy('date', 'DESC')->get();
    }

    /**
     * @param $user_id = Authenticated user
     * @return     public function getOpenByUserId($user_id): int

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