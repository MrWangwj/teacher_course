<?php

namespace App\Http\Controllers\Wechat;

use App\Model\Setting;
use App\Model\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CourseController extends Controller
{
    //
    public function count(){
        $collection = Teacher::with('courses')->orderBy('name_py')->get();

        $teachers = [];

        foreach ($collection->toArray() as $value){
            $teachers['t'.$value['id']] = $value;
        }


        $nowWeek = Setting::getNowWeek();
        return compact(['teachers', 'nowWeek']);
    }

}
