<?php
/**
 * Created by PhpStorm.
 * User: wangweijin
 * Date: 2018/3/6
 * Time: 下午2:35
 */

Route::prefix('/wechat')->group(function (){

    Route::get('/teacher/type', 'Wechat\TeacherController@types');
    Route::get('/course/count', 'Wechat\CourseController@count');

    //登录页面
    Route::get('/login',function (){
        return view('wechat.login');
    });
    //登录检测
    Route::post('/login/check','Wechat\TeacherController@login');
    //教师显示课表页面
    Route::group(['middleware' => ['wechat.login']],function (){
        Route::get('/', function (){
            return view('wechat.index');
        });
        Route::post('/show/course','Wechat\TeacherController@showCourse');
        Route::post('/leave/course','Wechat\TeacherController@leaveCourse');
    });
});