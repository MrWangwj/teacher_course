<?php
/**
 * Created by PhpStorm.
 * User: wangweijin
 * Date: 2018/3/6
 * Time: 下午2:35
 */

Route::prefix('/wechat')->group(function (){
    Route::get('/', function (){
        return view('wechat.index');
    });


    Route::get('/teacher/type', 'Wechat\TeacherController@types');
    Route::get('/course/count', 'Wechat\CourseController@count');
});