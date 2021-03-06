<?php
/**
 * Created by PhpStorm.
 * User: wangweijin
 * Date: 2018/3/6
 * Time: 下午2:36
 */

Route::prefix('admin')->namespace('Admin')->group(function (){
    //用户登录
    Route::get('/login',function (){
        return view('admin.login');
    });
    Route::get('/login/validate', function () {
        return captcha_src();
    });

    Route::post('/login', 'LoginController@login');
    Route::get('/logout', 'LoginController@logout');


    //页面渲染

    Route::group(['middleware' => ['admin.login']], function (){
        Route::get('/', function (){
            return view('admin.index');
        });



        Route::prefix('teacher')->group(function (){
            Route::get('/type', 'TeacherController@type');
            Route::any('/data', 'TeacherController@data');
            Route::post('/add', 'TeacherController@add');
            Route::post('/edit', 'TeacherController@edit');
            Route::post('/delete', 'TeacherController@delete');


            Route::post('/course/get', 'TeacherController@teacherCourses');
            Route::post('/course/clear', 'TeacherController@clearCourse');
            Route::post('/course/add', 'TeacherController@addCourse');
            Route::post('/course/edit', 'TeacherController@editCourse');
            Route::post('/course/delete', 'TeacherController@deleteCourse');


            Route::any('/course/verification', 'ImportController@sendCode');
            Route::any('/teacherId', 'ImportController@teacherId');
            Route::any('/code', 'ImportController@code');


            //教师分页查询
            Route::post('/search/name','TeacherController@getTeacherInformation');
            Route::any('/s', 'ImportController@s');

            //一键导入所有老师的课
            Route::post('/teachers/import','ImportController@teachersCourse');
        });


        Route::prefix('setting')->group(function (){
            Route::get('/teacher/names', 'TeacherController@getTeacherNames');

            Route::get('/staffroom', 'StaffroomController@staffrooms');
            Route::post('/staffroom/create', 'StaffroomController@create');
            Route::post('/staffroom/edit', 'StaffroomController@edit');
            Route::post('/staffroom/delete', 'StaffroomController@delete');
            Route::post('/staffroom/sorting', 'StaffroomController@sorting');

            Route::get('/joblevels', 'JoblevelController@joblevels');
            Route::post('/joblevel/create', 'JoblevelController@create');
            Route::post('/joblevel/edit', 'JoblevelController@edit');
            Route::post('/joblevel/delete', 'JoblevelController@delete');
            Route::post('/joblevel/sorting', 'JoblevelController@sorting');

            Route::get('/titles', 'TitleController@titles');
            Route::post('/title/create', 'TitleController@create');
            Route::post('/title/edit', 'TitleController@edit');
            Route::post('/title/delete', 'TitleController@delete');
            Route::post('/title/sorting', 'TitleController@sorting');

            Route::get('/jobtypes', 'JobtypeController@jobtypes');
            Route::post('/jobtype/create', 'JobtypeController@create');
            Route::post('/jobtype/edit', 'JobtypeController@edit');
            Route::post('/jobtype/delete', 'JobtypeController@delete');
            Route::post('/jobtype/sorting', 'JobtypeController@sorting');

            Route::get('/terms', 'TermController@terms');
            Route::post('/term/now/set', 'TermController@setNowTerm');
            Route::post('/term/create', 'TermController@create');
            Route::post('/term/edit', 'TermController@edit');
            Route::post('/term/delete', 'TermController@delete');


        });
    });


});
Route::get('/admin/nodes','SettingController@nodes');