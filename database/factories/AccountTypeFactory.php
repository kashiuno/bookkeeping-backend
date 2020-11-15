<?php

/** @var Factory $factory */

use App\Models\Bookkeeping\AccountType;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

$factory->define(
    AccountType::class,
    function (Faker $faker) {
        return [
            'name'    => $faker->word,
            'user_id' => factory(User::class),
        ];
    }
);
