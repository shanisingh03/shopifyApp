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


// Deafault View
Route::get('/', 'AuthController@index')->name('app.auth');

Route::get('/home/{shop}', 'AuthController@home')->name('app.home');

// Install The App
Route::get('/installapp', 'AuthController@installApp')->name('app.install');

// CallBack URl
Route::get('/callback', 'AuthController@CallBack')->name('app.callback');

// Get Orders
Route::get('/orders/{shop}', 'HomeController@getOrders')->name('orders');


// Billable
Route::get('/billable', 'AuthController@Billable')->name('app.billable');


