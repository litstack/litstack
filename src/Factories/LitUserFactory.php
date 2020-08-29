<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Lit\Models\User;

$factory->define(User::class, function (Faker $faker, $args) {
    return [
        'first_name' => $faker->firstName,
        'last_name'  => $faker->lastName,
        'email'      => $faker->unique()->safeEmail,
        'username'   => $faker->unique()->userName,
        'password'   => bcrypt('secret'),
    ];
});
