<?php

namespace App\Repositories\Eloquent;

use App\Models\Banner;
use App\Services\Images\ImageService;
use Illuminate\Support\Collection;

class BannerRepository
{
    protected $model;
    private $filedisk = 'banners';

    public function __construct(Banner $model)
    {
        $this->model = $model;
    }

    /**
     * Obtener todos los banners
     */
    public function all(): Collection
    {
        return $this->model->orderByDesc('id')->get();
    }

    /**
     * Crear un nuevo banner
     */
    public function create(array $data): Banner
    {
        return $this->model->create($data);
    }

    /**
     * Crear un banner a partir de un request
     */
    public function createByRequest($request): Banner
    {
        $data = $request->validated();
        $banner = $this->create($data);
        if ($request->hasFile('image_banner')) {
            $this->saveImage($banner, $request->file('image_banner'));
        }
        return $banner;
    }

    /**
     * Actualizar un banner a partir de un request
     */
    public function updateByRequest($request, Banner $banner): bool
    {
        $data = $request->validated();
        $updated = $this->update($banner, $data);
        if ($request->hasFile('image_banner')) {
            $this->saveImage($banner, $request->file('image_banner'));
        }
        return $updated;
    }

    /**
     * Actualizar un banner
     */
    public function update(Banner $banner, array $data): bool
    {
        
        return $banner->update($data);
    }

    /**
     * Eliminar un banner
     */
    public function delete(Banner $banner): bool
    {
        return $banner->delete();
    }

    /**
     * Buscar un banner por ID
     */
    public function find($id): ?Banner
    {
        return $this->model->find($id);
    }

     /**
     * Almacena imagen de un banner llamando al servicio de Imagenes
     * 
     * @param banner
     * @param file
     * @return void
     */
    public function saveImage(Banner $banner, $file): void
    {
        $url = ImageService::save($this->filedisk, $file);

        if ($url) {
            $banner->image_banner = $url;
            $banner->save();
        }
    }
}
