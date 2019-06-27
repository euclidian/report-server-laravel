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
    return view('auth.login');
});

Auth::routes();

Route::get('/profile', 'HomeController@index')->name('home');
Route::get('/template', 'HomeController@index')->name('home');
Route::get('/print', 'HomeController@index')->name('home');
Route::get('/print_transaction', 'HomeController@index')->name('home');
Route::get('/users', 'HomeController@index')->name('home');
Route::get('/template_gallery', 'HomeController@index')->name('home');
