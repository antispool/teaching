<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('', 'Api\DefaultController@index');

Route::get('student', 'Api\StudentController@index');
Route::get('student/{id}', 'Api\StudentController@get');
Route::put('student/{id}', 'Api\StudentController@put');
Route::post('student', 'Api\StudentController@post');
Route::delete('student/{id}', 'Api\StudentController@delete');

Route::get('college', 'Api\CollegeController@index');
Route::get('college/{id}', 'Api\CollegeController@get');
Route::put('college/{id}', 'Api\CollegeController@put');
Route::post('college', 'Api\CollegeController@post');
Route::delete('college/{id}', 'Api\CollegeController@delete');

Route::put('college/{id}/student', 'Api\CollegeController@putStudents');
Route::get('college/{id}/student', 'Api\CollegeController@getStudents');
Route::delete('college/{id}/student', 'Api\CollegeController@deleteStudents');