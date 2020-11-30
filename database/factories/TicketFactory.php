<?php

use App\Infrastructure\Models\Ticket;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Ticket::class, function (Faker $faker, $attrs) {
    return [
        'id' => Str::uuid(),
        'deviceId' => $attrs['deviceId'] ?? Str::uuid(),
        'stake' => $attrs['stake'] ?? 100
    ];
});
