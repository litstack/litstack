<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Fjord\User\Models\FjordUser;

$factory->define(FjordUser::class, function (Faker $faker, $args) {
    return [
        'first_name' => $faker->firstName,
        'last_name'  => $faker->lastName,
        'email'      => $faker->unique()->safeEmail,
        'username'   => $faker->unique()->userName,
        'password'   => bcrypt('secret'),
    ];
});
