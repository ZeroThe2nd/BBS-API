<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name'  => $faker->name,
        'email' => $faker->email,
    ];
});

/**
 * Factory definition for model App\User.
 */
$factory->define(App\User::class, function ($faker) {
    return [// Fields here
    ];
});

/**
 * Factory definition for model App\Board.
 */
$factory->define(App\Board::class, function ($faker) {
    return [// Fields here
    ];
});

/**
 * Factory definition for model App\Thread.
 */
$factory->define(App\Thread::class, function ($faker) {
    return [// Fields here
    ];
});

/**
 * Factory definition for model App\Post.
 */
$factory->define(App\Post::class, function ($faker) {
    return [// Fields here
    ];
});
