<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

interface BoxRepositoryInterface
{
    /**
     * @param $user_id = Authenticated user
     * @return Model
     */
    public function getOpenByUserId($user_id): Model;
}