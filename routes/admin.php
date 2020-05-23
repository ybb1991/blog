<?php


Route::get('login', 'Auth\LoginController@showLoginForm');
Route::post('login', 'Auth\LoginController@login');

// Route::get('laravel_logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');

Route::group(['middleware' => 'admin'], function () {    
    Route::get('/', 'IndexController@index');
});