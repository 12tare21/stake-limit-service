<?php

namespace App\Services\Utills;

use Illuminate\Support\Facades\Validator;

class ValidatorExtender{
    public static function extends()
    {
        self::notBetween();
    }
    
    public static function notBetween()
    {
        Validator::extend('not_between', function ($attribute, $value, $parameters)
        {
            if($value > intval($parameters[0]) && $value < intval($parameters[1])){
                return false;
            }
            
            return true;
        });
    }
}