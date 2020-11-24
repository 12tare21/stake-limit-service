<?php

namespace App\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Infrastructure\Repositories\Interfaces\Repository;

class MutableRepository extends ReadRepository implements Repository{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        return $this->model->where($this->model->getKeyName(), $id)->update($data);
    }

    public function delete($id)
    {
        return $this->model->delete($id);
    }

    public function findOrCreate($id, array $data = [])
    {
        try{
            return $this->model->findOrFail($id);
        } catch (\Exception $e) {
            return $this->model->create($data);
        }
    }
}