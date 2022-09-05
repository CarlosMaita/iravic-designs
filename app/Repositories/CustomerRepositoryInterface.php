<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface CustomerRepositoryInterface
{
    public function debtorsToNotify(): Collection;
}