<?php

use Faker\Generator as Faker;

$factory->define(App\Status::class, function (Faker $faker) { 
    return [
        'content'    => $faker->text(),
        'user_id'    => 1,
    ];
});
