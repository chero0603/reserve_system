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

Auth::routes(['verify' => true]);

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function() {
    Route::get('/', 'HomeController@index')->name('admin.home');
    // home
    Route::get('home', 'HomeController@index')->name('admin.home');
    // login logout
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('login', 'Auth\LoginController@login')->name('admin.login');
    Route::post('logout', 'Auth\LoginController@logout')->name('admin.logout');
    // register
    Route::get('register', 'Auth\RegisterController@showRegisterForm')->name('admin.register');
    Route::post('register', 'Auth\RegisterController@register')->name('admin.register');
});

Route::get('/home', 'HomeController@index')->middleware('verified')->name('home');

