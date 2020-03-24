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

Route::get('/', 'HomeController@index')->name('home');

Route::middleware('verified')->group( function() {
    Route::get('/home', 'HomeController@index')->name('home');
});

Auth::routes(['verify' => true, 'reset' => true]);

Route::group(['namespace' => 'Social', 'prefix' => 'social', 'name' => 'social.', 'as' => 'social.'], function() {
    Route::get('/login/{provider}', 'Auth\LoginController@socialOauth')->name('login');
    Route::get('/callback/{provider}', 'Auth\LoginController@handleSocialCallback')->name('callback');
    Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
    Route::post('/register', 'Auth\RegisterController@register')->name('post.register');
});

Route::group(['namespace' => 'Admin', 'prefix' => 'admin', 'name' => 'admin.', 'as' => 'admin.'], function() {
    Auth::routes(['verify' => true, 'reset' => true]);
    Route::middleware('verified')->group( function() {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('/home', 'HomeController@index')->name('home');
    });
});
