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


Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/test', 'LearnController@test')->name('test');
Route::get('/course/{id_course}', 'CourseController@index')->name('course');
Route::post('/course/{id_course}/learn/new', 'LearnController@new')->name('learn/new');
Route::get('/course/{id_course}/learn/{id_spreadsheet}', 'LearnController@index')->name('learn');
Route::get('/course/{id_course}/learn/{id_spreadsheet}/edit', 'LearnController@edit')->name('edit');
Route::post('/course/{id_course}/learn/{id_spreadsheet}/edit/save', 'LearnController@save')->name('edit/save');
Route::post('/course/{id_course}/learn/{id_spreadsheet}/submit', 'LearnController@submit')->name('learn/submit');