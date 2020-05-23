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

Route::group(['prefix'=>'admin', 'namespace'=>'Admin'], function(){
    \Composer\Autoload\includeFile(__DIR__ . '/admin.php');
});

Route::group(['prefix'=>'agent', 'namespace'=>'Agent'], function(){
    \Composer\Autoload\includeFile(__DIR__ . '/agent.php');
});

// 小程序
// Route::group(['prefix'=>'api', 'namespace'=>'Mini'], function(){
//     \Composer\Autoload\includeFile(__DIR__ . '/mini.php');
// });

