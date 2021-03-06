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

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/', 'HomeController@index')->name('home')->middleware('auth');
Route::get('/data/{type}/{id?}', 'DataUploadController@index');

Route::post('data/add', 'DataUploadController@store');
Route::post('data/edit/{id}', 'DataUploadController@edit');
Route::post('data/delete/{id}', 'DataController@destroy');

Auth::routes();
