<?php

use App\Infrastructure\Models\Ticket;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Ticket::class, function (Faker $faker, $atts) {
    return [
        'id' => Str::uuid(),
        'deviceId' => $atts['deviceId'] ?? Str::uuid(),
        'stake' => 100
    ];
});
