<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('login', 'Auth\LoginController@login');

// Protected routes
Route::middleware('auth:api')->group(function () {
    Route::post('logout', 'Auth\LoginController@logout');
    Route::get('user', 'Auth\LoginController@user');
    Route::get('dashboard', 'HomeController@dashboard');
});
