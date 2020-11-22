<?php

namespace App\Infrastructure\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Model;
use App\Infrastructure\Repositories\Interfaces\IRepository;

class MutableRepository extends ReadRepository implements IRepository{
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    public function store(array $data)
    {//na device biljezit zbir stakeova,mozda stake i ne kreirati
        return $this->_model->store($data);
    }

    public function update($id, array $data)
    {
        return $this->_model->where($id)->update($data);
    }

    public function delete($id)
    {
        return $this->_model->delete($id);
    }
}