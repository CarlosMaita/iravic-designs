<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;  
use Illuminate\Support\Collection;

interface VisitRepositoryInterface
{
    public function all($params = null): Collection;

    public function allBySchedule($schedule_id, $zones = null, $roles = null): Collection;

    public function completeByDateUser($customer_id, $date): bool;

    public function hasCustomerVisitForDate($date, $customer_id): int;

    public function getCountCustomersFromZone($date, $customer_id, $zone_id): int;
}