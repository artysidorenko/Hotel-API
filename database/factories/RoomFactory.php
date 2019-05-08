<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\Room;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
 */

$factory->define(Room::class, function (Faker $faker) {
    return [
        'beds' => $faker->randomElement($array = array(1, 2, 3, 4)),
        'floor' => $faker->randomElement($array = array(1, 2, 3, 4, 5, 6)),
        'price' => $faker->randomElement($array = array(60, 70, 80, 100, 120, 150)),
        'available' => true,
    ];
});
