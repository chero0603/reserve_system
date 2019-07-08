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

Auth::routes(['verify' => true, 'reset' => true]);

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'name' => 'admin.', 'as' => 'admin.'], function() {
    Auth::routes(['verify' => true, 'reset' => true]);
    Route::get('/', 'HomeController@index')->middleware('verified:admin')->name('home');
    Route::get('/home', 'HomeController@index')->middleware('verified:admin')->name('home');
});

Route::get('/home', 'HomeController@index')->middleware('verified')->name('home');

