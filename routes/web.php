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
Route::get('/test', function () {
    return view('test');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/course/{id_course}', 'CourseController@index')->name('course');
Route::get('/course/{id_course}/learn/{id_spreadsheet}', 'LearnController@index')->name('learn');