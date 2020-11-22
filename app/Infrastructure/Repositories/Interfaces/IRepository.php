<?php

namespace App\Infrastructure\Repositories\Interfaces;

interface IRepository{
    function all();
    function findById($id);
    function findByAttribute($attr, $value);
    function store(array $data);
    function update($id, array $data);
    function delete($id);
}