<?php

return [
    /*
    |--------------------------------------------------------------------------
    | ImportController url for request
    |--------------------------------------------------------------------------
    |
    */

    'url'=>[
        'teacher_id'=> 'http://jwgl.hist.edu.cn/jwweb/ZNPK/Private/List_JS.aspx?xnxq=',  //获取老师id的url
        'code'=>'http://jwgl.hist.edu.cn/jwweb/sys/ValidateCode.aspx',                   //返回二维码视图的url
        'teacher'=>'http://jwgl.hist.edu.cn/jwweb/ZNPK/TeacherKBFB_rpt.aspx'             //返回课表视图的url
    ],

    'headers'=>'ZNPK/TeacherKBFB.aspx',                                                  //课表请求的headers

];