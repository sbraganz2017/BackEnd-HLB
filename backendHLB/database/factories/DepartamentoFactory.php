<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Departamento;
use Faker\Generator as Faker;

$factory->define(Departamento::class, function (Faker $faker) {
    return [
        'nombre' => $faker->name,
        'id_organizacion' => ''
    ];
});
