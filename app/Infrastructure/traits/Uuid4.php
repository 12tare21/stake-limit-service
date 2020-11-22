<?php

namespace App\Infrastructure\Traits;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

trait Uuid4{
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Uuid::uuid4());
        });
    }

    public function getIncrementing()
    {
        return false;
    }

    public function getKeyType()
    {
        return 'string';
    }
}