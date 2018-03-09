<?php
/**
 * Created by PhpStorm.
 * User: wangweijin
 * Date: 2018/3/6
 * Time: 下午2:36
 */

Route::prefix('admin')->group(function (){
    //用户登录
    Route::get('/login',function (){
        return view('admin.login');
    });
    Route::get('/login/validate', function () {
        return captcha_src();
    });

    Route::post('/login', 'Admin\LoginController@login');
    Route::get('/logout', 'Admin\LoginController@logout');


    //页面渲染

    Route::group(['middleware' => ['admin.login']], function (){
        Route::get('/', function (){
            return view('admin.index');
        });

        Route::get('/nodes','SettingController@nodes');

        Route::prefix('teacher')->group(function (){
            Route::get('/type', 'Admin\TeacherController@type');
            Route::get('/data', 'Admin\TeacherController@data');
            Route::post('/add', 'Admin\TeacherController@add');
            Route::post('/edit', 'Admin\TeacherController@edit');
            Route::post('/delete', 'Admin\TeacherController@delete');


            Route::post('/course/get', 'Admin\TeacherController@teacherCourses');
            Route::post('/course/clear', 'Admin\TeacherController@clearCourse');
            Route::post('/course/add', 'Admin\TeacherController@addCourse');
            Route::post('/course/edit', 'Admin\TeacherController@editCourse');
            Route::post('/course/delete', 'Admin\TeacherController@deleteCourse');
        });

    });


});