<?php

namespace App\Repositories\Eloquent;

use App\Models\HomeCta;
use Illuminate\Support\Collection;

class HomeCtaRepository
{
    protected $model;

    public function __construct(HomeCta $model)
    {
        $this->model = $model;
    }

    /**
     * Get all CTAs ordered
     */
    public function all(): Collection
    {
        return $this->model->ordered()->get();
    }

    /**
     * Create a new CTA
     */
    public function create(array $data): HomeCta
    {
        return $this->model->create($data);
    }

    /**
     * Create a CTA from a request
     */
    public function createByRequest($request): HomeCta
    {
        $data = $request->validated();
        return $this->create($data);
    }

    /**
     * Update a CTA from a request
     */
    public function updateByRequest($request, HomeCta $homeCta): bool
    {
        $data = $request->validated();
        return $this->update($homeCta, $data);
    }

    /**
     * Update a CTA
     */
    public function update(HomeCta $homeCta, array $data): bool
    {
        return $homeCta->update($data);
    }

    /**
     * Delete a CTA
     */
    public function delete(HomeCta $homeCta): bool
    {
        return $homeCta->delete();
    }

    /**
     * Find a CTA by ID
     */
    public function find($id): ?HomeCta
    {
        return $this->model->find($id);
    }
}
