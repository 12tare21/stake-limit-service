<?php

namespace App\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'stake',
        'deviceId',
        'id'
    ];    

    public function device(){
        return $this->belongsTo(Device::class);
    }  
}
