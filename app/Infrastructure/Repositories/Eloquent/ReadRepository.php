<?php

namespace App\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;

class ReadRepository{
    private $_model;

    public function __construct(Model $model)
    {
        $this->_model = $model;
    }

    public function all()
    {
        return $this->_model->all();
    }

    public function findById($id)
    {
        return $this->_model->findById($id);
    }

    public function findByAttribute($attr, $value)
    {
        return $this->_model->where($attr, $value)->get();
    }
}