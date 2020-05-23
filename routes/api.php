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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


$api = app('Dingo\Api\Routing\Router');

$api->version('manage', [

], function($api){
    $api->group([
        'namespace' => 'App\Http\Controllers\Manage'
    ], function($api){

        $api->post('decode', 'UserController@decode');       // 发验证码
        $api->post('login', 'UserController@login');        // 登录

        // $api->resource('lesson', 'LessonController');
    
        // 登录才能进入
        $api->group(['middleware' => 'api.manage.token'], function($api){
                
            $api->get('users/{id}', 'UserController@show');
        });
    });

});

$api->version('v1', [

], function($api){
    $api->group([
        'namespace' => 'App\Http\Controllers\Api'
    ], function($api){
        $api->group(['middleware' => 'api.token'], function($api){
            $api->resource('lesson', 'LessonsController');
        });
    });
});


