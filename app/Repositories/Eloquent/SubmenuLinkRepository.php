<?php

namespace App\Repositories\Eloquent;

use App\Models\SubmenuLink;
use Illuminate\Support\Collection;

class SubmenuLinkRepository
{
    protected $model;

    public function __construct(SubmenuLink $model)
    {
        $this->model = $model;
    }

    /**
     * Get all submenu links
     */
    public function all(): Collection
    {
        return $this->model->ordered()->get();
    }

    /**
     * Get all active submenu links ordered
     */
    public function getActive(): Collection
    {
        return $this->model->active()->ordered()->get();
    }

    /**
     * Create a new submenu link
     */
    public function create(array $data): SubmenuLink
    {
        return $this->model->create($data);
    }

    /**
     * Create a submenu link from request
     */
    public function createByRequest($request): SubmenuLink
    {
        $data = $request->validated();
        return $this->create($data);
    }

    /**
     * Update a submenu link from request
     */
    public function updateByRequest($request, SubmenuLink $submenuLink): bool
    {
        $data = $request->validated();
        return $this->update($submenuLink, $data);
    }

    /**
     * Update a submenu link
     */
    public function update(SubmenuLink $submenuLink, array $data): bool
    {
        return $submenuLink->update($data);
    }

    /**
     * Delete a submenu link
     */
    public function delete(SubmenuLink $submenuLink): bool
    {
        return $submenuLink->delete();
    }

    /**
     * Find a submenu link by ID
     */
    public function find($id): ?SubmenuLink
    {
        return $this->model->find($id);
    }
}
