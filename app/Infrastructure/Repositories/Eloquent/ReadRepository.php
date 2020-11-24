<?php

namespace App\Infrastructure\Repositories\Eloquent;

class ReadRepository{
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
}