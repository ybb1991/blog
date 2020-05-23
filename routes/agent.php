<?php


// Route::group(['middleware'=>'agent'], function(){
//       Route::get('/', "LessonController@index");
// });
Route::get('/', 'LessonController@index');


// agent中间件是用来判断是否登录，登录后才能进
// Route::group(['middleware' => 'agent'], function () {    
//       Route::get('get_data', 'LessonController@getData');
//   });