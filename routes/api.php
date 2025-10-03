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
    
    Route::middleware(['check.permission:view_orders'])->group(function () {
        Route::get('orders', 'OrderController@index');
        Route::get('orders/{id}', 'OrderController@show');
    });
    Route::middleware(['check.permission:create_orders'])->post('orders', 'OrderController@store');
    Route::middleware(['check.permission:update_orders'])->put('orders/{id}', 'OrderController@update');
    Route::middleware(['check.permission:delete_orders'])->delete('orders/{id}', 'OrderController@destroy');

    Route::get('clients', 'ClientController@index');
});
