<?php

use Faker\Generator as Faker;

// $factory->define(Model::class, function (Faker $faker) {
//     return [
//         //
//     ];
// });

$factory->define(App\Models\Lesson::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'body' => $faker->paragraph,
        'free' => $faker->boolean(), // secret
    ];
});
