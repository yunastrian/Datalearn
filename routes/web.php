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
Auth::routes();
Route::get('/', 'HomeController@index')->name('home');
Route::redirect('/home', '/');
Route::get('/test', 'AutograderController@test')->name('test');
Route::post('/profile', 'HomeController@profile')->name('profile');
Route::get('/course/{id_course}', 'CourseController@index')->name('course');
Route::post('/course/new', 'CourseController@new')->name('course/new');
Route::post('/course/enroll', 'CourseController@enroll')->name('course/enroll');
Route::post('/course/{id_course}/delete', 'CourseController@delete')->name('course/delete');
Route::post('/course/{id_course}/learn/new', 'LearnController@new')->name('learn/new');
Route::get('/course/{id_course}/learn/{id_topic}', 'LearnController@index')->name('learn');
Route::get('/course/{id_course}/learn/{id_topic}/edit', 'LearnController@edit')->name('edit');
Route::post('/course/{id_course}/learn/{id_topic}/delete', 'LearnController@delete')->name('learn/delete');
Route::post('/course/{id_course}/learn/{id_topic}/edit/save', 'LearnController@save')->name('edit/save');
Route::post('/course/{id_course}/learn/{id_topic}/submit', 'AutograderController@index')->name('autograder');