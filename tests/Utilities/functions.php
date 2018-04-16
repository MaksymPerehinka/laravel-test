<?php

use App\User;

/**
 * Created by PhpStorm.
 * User: User
 * Date: 4/15/2018
 * Time: 10:50 PM
 */

function create($class, $attributes = [])
{
    return factory($class)->create($attributes);
}

function make($class, $attributes = [])
{
    return factory($class)->make($attributes);
}

function registerUser($test_instance)
{
    $user = make('App\User')->toArray();
    $user['password_confirmation'] = $user['password'] = str_random(8);

    $test_instance->post('/register', $user);

    $user = User::where('email', $user['email'])->firstOrFail();
    return $user;
}