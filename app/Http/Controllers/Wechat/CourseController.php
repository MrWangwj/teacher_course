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
        $term_id = Setting::getNowTermId();

        $collection = Teacher::with(['courses' => function($query) use($term_id){
            $query->where('term_id', $term_id);
        }])->orderBy('name_py')->get();

        $teachers = [];

        foreach ($collection->toArray() as $value){
            $teachers['t'.$value['id']] = $value;
        }

        $nowWeek = Setting::getNowWeek();
        return compact(['teachers', 'nowWeek']);
    }

}
