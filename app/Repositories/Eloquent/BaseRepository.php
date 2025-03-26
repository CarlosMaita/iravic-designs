<?php   

namespace App\Repositories\Eloquent;   

use App\Repositories\EloquentRepositoryInterface; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository implements EloquentRepositoryInterface 
{     
    /**      
     * @var Model      
     */     
    protected $model;       

    /**      
     * BaseRepository constructor.      
     *      
     * @param Model $model      
     */     
    public function __construct(Model $model)     
    {         
        $this->model = $model;
    }
    
    /**
    * @return Collection
    */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
    * @param array $attributes
    * @return Model
    */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }
 
    /**
    * @param $id
    * @return Model
    */
    public function find($id): ?Model
    {
        return $this->model->findOrFail($id);
    }

    /**
    * @param $id
    * @return Model
    */
    public function findOnly($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
    * @param $id 
    * @param array $attributes
    * @return Bool
    */
    public function update($id, array $attributes): bool
    {
        return $this->model->find($id)->update($attributes);
    }

    /**
    * @param $id 
    * @return Bool
    */
    public function delete($id): bool
    {
        return $this->model->find($id)->delete();
    }
}