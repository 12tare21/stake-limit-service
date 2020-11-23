<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

class ValidatorExtender{
    public static function extends()
    {
        self::notBetween();
    }
    
    private static function notBetween()
    {
        Validator::extend('not_between', function ($attribute, $value, $parameters)
        {
            if($value > doubleval($parameters[0]) && $value < doubleval($parameters[1])){
                return false;
            }
            
            return true;
        });
    }
}