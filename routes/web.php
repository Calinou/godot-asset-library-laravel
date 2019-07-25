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

// Routing is handled using Vue.js, so the backend just needs to return the HTML shell
// regardless of the initial URL.
Route::get('/{any}', 'SpaController@index')->where('any', '.*');
