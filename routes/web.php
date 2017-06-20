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
Route::get('/', 'DashboardController@index');
Route::get('broadcast', 'DashboardController@broadcast');

Route::get('/home', 'DashboardController@index')->name('home');
Route::get('report','ReportController@index');
Route::get('newsReport','NewsReportController@index');


Route::post('broadcast/addBroadcast','DashboardController@addBroadcast');