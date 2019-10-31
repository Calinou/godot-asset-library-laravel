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

Route::get('/', 'AssetController@index')->name('asset.index');
// Redirect for compatibility with the old asset library homepage URL
Route::permanentRedirect('/asset', '/');
Route::get('/asset/submit', 'AssetController@create')->name('asset.create')->middleware('verified');
Route::post('/asset', 'AssetController@store')->name('asset.store')->middleware('verified');

Route::get('/asset/{asset}', 'AssetController@show')->name('asset.show');
Route::get('/asset/{asset}/edit', 'AssetController@edit')->name('asset.edit')->middleware('can:edit-asset,asset');
Route::put('/asset/{asset}', 'AssetController@update')->name('asset.update')->middleware('can:edit-asset,asset');

Route::get('/admin', 'AdminController@index')->name('admin.index')->middleware('can:admin');

// Register authentication-related routes (including email verification routes)
Auth::routes(['verify' => true]);

// OAuth2 authentication routes
Route::get('login/{provider}', 'Auth\LoginController@redirectToProvider')->name('login.oauth2');
Route::get('login/{provider}/callback', 'Auth\LoginController@handleProviderCallback')->name('login.oauth2.callback');
