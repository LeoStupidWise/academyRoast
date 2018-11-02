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

Route::get('/', 'Web\AppController@getApp') -> middleWare('auth');

Route::get('/login', 'Web\AppController@getLogin')
    ->name('login')
    ->middleware('guest');

// 第三方登录
Route::get('/auth/{social}', 'Web\AuthenticationController@getSocialRedirect')
    ->middleware('guest');
Route::get('/auth/{social}/callback', 'Web\AuthenticationController@getSocialCallback')
    ->middleware('guest');
