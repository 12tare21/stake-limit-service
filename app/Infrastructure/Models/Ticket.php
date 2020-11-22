<?php

namespace App\Infrastructure\Models;

use App\Infrastructure\traits\Uuid4;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use Uuid4;

    protected $table = 'tickets';
    protected $keyType = 'string';
    
    protected $fillable = [
        'stake',
        'deviceId',
        'id'
    ];    

    public function device(){
        return $this->belongsTo(Device::class);
    }  
}
