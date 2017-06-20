<?php

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

Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('register', 'AuthenticateController@register');
Route::get('/', function() {
    return 'a';
});


Route::group(['middleware' => ['jwt.auth']], function () {
    Route::get('reports', 'ReportController@index');
    Route::post('reports', 'ReportController@store');
    Route::get('reports/near', 'ReportController@near');
    Route::get('reports/{user}', 'ReportController@show');
    Route::get('news', 'NewsController@index');
    Route::post('news', 'NewsController@store');
});