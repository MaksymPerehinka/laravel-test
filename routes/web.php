<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/user/confirm', 'UserController@confirm')->name('user.confirm');

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/user/confirm', 'UserController@confirm')->name('user.confirm');

Route::middleware('guest')->group(function () {
    Route::get('/user/{user}/get-confirmation-token', 'UserController@requestConfirmationToken')->name('user.confirm.request_token');
});
