<?php

namespace App\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ReadRepository{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function findByAttribute($attr, $value)
    {
        return $this->model->where($attr, $value)->get();
    }

    public function sumByAttribute($propName, $attr, $value)
    {
        return $this->model->where($attr, $value)->sum($propName);
    }

    public function sumByFilters($filters, $propName)
    {
        return $this->model->where($filters)->sum($propName);
    }
}