<?php

use Illuminate\Support\Facades\Route;

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::get('/{any}', 'HomeController@index')->where('any', '.*');