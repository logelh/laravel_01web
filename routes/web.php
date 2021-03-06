<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'StaticPagesController@home')->name('home');
/**
 * 如果要使用<li><a href="{{ route('help') }}">帮助</a></li> 这种方式来调用help路由,则需要在路由后面链式调用 name 方法来为路由指定名称
 * name 命名路由’
 */
Route::get('/help', 'StaticPagesController@help')->name('help');
Route::get('/about', 'StaticPagesController@about')->name('about');

Route::get('signup', 'UserController@create')->name('signup');

/**
 *  Route::get('/users', 'UsersController@index')->name('users.index');
    Route::get('/users/create', 'UsersController@create')->name('users.create');
    Route::get('/users/{user}', 'UsersController@show')->name('users.show');
    Route::post('/users', 'UsersController@store')->name('users.store');
    Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
    Route::patch('/users/{user}', 'UsersController@update')->name('users.update');
    Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');
 *  这段代码等同于下面一段  遵从RESTful 原则
 */
Route::resource('users', 'UserController');




Route::get('login', 'SessionsController@create')->name('login');
Route::post('login', 'SessionsController@store')->name('login');
Route::delete('logout', 'SessionsController@destroy')->name('logout');


Route::get('signup/confirm/{token}', 'UserController@confirmEmail')->name('confirm_email');

/**
 * 邮箱确认
 */
Route::get('password/reset',  'PasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email',  'PasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}',  'PasswordController@showResetForm')->name('password.reset');
Route::post('password/reset',  'PasswordController@reset')->name('password.update');

// 微博创建
Route::resource('statuses', 'StatusesController', ['only' => ['store', 'destroy']]);
