<?php

namespace App\Infrastructure\Repositories\Interfaces;

interface Repository{
    function all();
    function find($id);
    function findByAttribute($attr, $value);
    function create(array $data);
    function update($id, array $data);
    function delete($id);
    function findOrCreate($id, array $data = []);
    function sumByAttribute($propName, $attr, $value);
    function sumByFilters($filters, $propName);
}