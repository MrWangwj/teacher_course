<?php

namespace App\Http\Controllers\Wechat;

use App\Model\Admin;
use App\Model\Joblevel;
use App\Model\Jobtype;
use App\Model\Staffroom;
use App\Model\Teacher;
use App\Model\Term;
use App\Model\Title;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class TeacherController extends Controller
{
    //
    public function types(){
        $joblevels = Joblevel::orderBy('sort')->get(['id', 'name']);
        $jobtypes = Jobtype::orderBy('sort')->get(['id', 'name']);
        $staffrooms = Staffroom::orderBy('sort')->get(['id', 'name']);
        $titles = Title::orderBy('sort')->get(['id', 'name']);
        return compact(['joblevels', 'jobtypes', 'staffrooms', 'titles']);
    }

    public function login(){
        $name = Input::get("name");
        if ($name==""){
            return $this->returnJSON(-2, '教师姓名不存在');
        }
        $user = Teacher::where("name",$name)->get();
        if($user){
            session(['wechat' => $user]);
            return $this->returnJSON(1, 'success');
        }
        return $this->returnJSON(-2, '教师姓名不存在');
    }

    public function showCourse(){
        $teacher = Input::get('teacher');
        $id = Teacher::where('name',$teacher)->get(['id']);
        $course = Teacher::find($id[0]->id)->courses()->get();
        $trem = Term::orderBy('name', 'desc')->get();
        return [$course,$trem[0]->start_school];
    }

    public function leaveCourse(){
        \request()->session()->forget("wechat");
    }
}
