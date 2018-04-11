<?php

namespace App\Http\Controllers\Admin;

use App\Model\Course;
use App\Model\Joblevel;
use App\Model\Jobtype;
use App\Model\Setting;
use App\Model\Staffroom;
use App\Model\Teacher;
use App\Model\Title;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Overtrue\LaravelPinyin\Facades\Pinyin;

class TeacherController extends Controller
{


    //获取教师信息
    function data(){
        $teachers = Teacher::with(['joblevel', 'jobtype', 'staffroom', 'title'])->orderBy('name_py')->get();
        return $teachers;
    }

    //获取教师类型
    function type(){
        $joblevels = Joblevel::orderBy('id')->get(['id', 'name']);
        $jobtypes = Jobtype::orderBy('id')->get(['id', 'name']);
        $staffrooms = Staffroom::orderBy('id')->get(['id', 'name']);
        $titles = Title::orderBy('id')->get(['id', 'name']);
        return compact(['joblevels', 'jobtypes', 'staffrooms', 'titles']);
    }

    function add(){
        $teacherData = \request(['name', 'sex', 'joblevel_id', 'jobtype_id', 'staffroom_id', 'title_id']);
        $teacherData['name_py'] = implode(' ', Pinyin::convert(\request('name')));
        $teacher = Teacher::create($teacherData);
        if($teacher)
            return $this->returnJSON(1, '添加成功');
        return $this->returnJSON(0, '添加失败');
    }

    function edit(){
        $teacher = Teacher::where('id', \request('id'))->first();
        $data = \request(['name', 'sex', 'joblevel_id', 'jobtype_id', 'staffroom_id', 'title_id']);
        $data['name_py'] = implode(' ', Pinyin::convert(\request('name')));
        $teacher->fill($data);
        $result = $teacher->save();

        if($result)
            return $this->returnJSON(1, '修改成功');
        return $this->returnJSON(0, '修改失败');
    }

    function delete(){
        $result = Teacher::destroy(\request('id'));
        if($result)
            return $this->returnJSON(1, '删除成功');
        return $this->returnJSON(0, '删除失败');
    }



    public function teacherCourses(){
        $term_id = Setting::getNowTermId();
        $teacher = Teacher::with(['courses' => function($query) use($term_id){
            $query->where('term_id', $term_id);
        }])->where('id', \request('id'))->first();

        if($teacher)
            return $this->returnJSON(1, 'success', $teacher);
        return $this->returnJSON(0, '人员不存在');
    }

    public function clearCourse(){
//        $this->validate(request(),[
//            'user-id' => 'required|numeric|exists:users,id',
//        ]);


        $term_id = Setting::getNowTermId();
        $user = Teacher::with(['courses' => function($query)use($term_id){
            $query->select(['courses.id'])->where('term_id', $term_id);
        }])->where('id', request('id'))->first();


        if(!$user)
            return $this->returnJSON(0, '清除失败');
        $course_ids = $user->courses->pluck('id')->toArray();
        $user->courses()->detach($course_ids);
        return ['code' => 1, 'msg' => '清除成功'];
    }


    public function addCourse(){
        //验证
//        $this->validate(\request(), [
//            'user-id' => 'required|numeric|exists:users,id',
//            'name' => 'required|max:150',
//            'teacher' => 'required|max:45',
//            'location' => 'required|max:150',
//            'start_week' => 'required|Integer|between:1,20',
//            'week_end' => 'required|Integer|between:1,20',
//            'section_start' => [
//                'required',
//                Rule::in([1, 3, 5, 6, 8, 10, 12]),
//            ],
//            'end_section' => [
//                'required',
//                Rule::in([2, 4, 5, 7, 9, 11, 12]),
//            ],
//            'week_day' => 'required|Integer|between:1,7',
//            'week_type' => [
//                'required',
//                Rule::in([0, 1, 2]),
//            ]
//        ]);
        $request = request();
        if($request['week_start'] > $request['week_end'] || $request['section_start'] > $request['section_end']){
            return ['code' => 0, 'msg' => '错误操作'];
        }

        $user = Teacher::with('courses')->find($request['id']);

        $week_day_course = $user->courses->where('week_day', $request['week_day']);

        $result = $week_day_course->filter(function ($value, $key) use($request){
            if($value->week_type ==0 || $value->week_type = $request['week_type']){
                if(max($request['section_start'], $value->section_start)
                    <= min($request['section_end'], $value->section_end)){

                    if(max($request['week_start'], $value->week_start)
                        <= min($request['week_end'], $value->week_end)){
                        return true;
                    }

                }
            }
        });

        if($result->count() > 0){
            return ['code' => 0, 'msg' => '课程冲突'.$result->first()->name];
        }


        //存储
        $term_id = Setting::getNowTermId();
        $data = request([
            'name',
            'teacher',
            'location',
            'week_start',
            'week_end',
            'section_start',
            'section_end',
            'week_day',
            'week_type'
        ]);
        $data['term_id'] = $term_id;
        $course = (Course::firstOrCreate($data));
        if(!$course){
            return ['code' => 1, 'msg' => '添加失败'];
        }
        $user->courses()->attach($course->id);


        return ['code' => 1, 'msg' => '添加成功'];
        //返回

    }

    public function editCourse(){
//        $this->validate(\request(), [
//            'user-id' => 'required|numeric|exists:users,id',
//            'id' => 'required|numeric|exists:courses,id',
//            'name' => 'required|max:150',
//            'teacher' => 'required|max:45',
//            'location' => 'required|max:150',
//            'week_start' => 'required|Integer|between:1,20',
//            'week_end' => 'required|Integer|between:1,20',
//            'section_start' => [
//                'required',
//                Rule::in([1, 3, 5, 6, 8, 0, 12]),
//            ],
//            'section_end' => [
//                'required',
//                Rule::in([2, 4, 5, 7, 9, 11, 12]),
//            ],
//            'week_day' => 'required|Integer|between:1,7',
//            'week_type' => [
//                'required',
//                Rule::in([0, 1, 2]),
//            ]
//        ]);
        $request = request();
        if($request['week_start'] > $request['week_end'] || $request['section_start'] > $request['section_end']){
            return ['code' => 0, 'msg' => '错误操作'];
        }

        $user = Teacher::with('courses')->find($request['user_id']);

        $week_day_course = $user->courses->where('week_day', $request['week_day']);

        $result = $week_day_course->filter(function ($value, $key) use($request){
            if($value->id == $request['id'])
                return false;
            if($value->week_type ==0 || $value->week_type = $request['week_type']){
                if(max($request['section_start'], $value->section_start)
                    <= min($request['section_end'], $value->section_end)){

                    if(max($request['week_start'], $value->week_start)
                        <= min($request['week_end'], $value->week_end)){
                        return true;
                    }

                }
            }
        });

        if(!$result->isEmpty()){
            return ['code' => 0, 'msg' => '课程冲突,'.$result->first()->name];
        }


        //存储
        $term_id = Setting::getNowTermId();
        $data = request([
            'name',
            'teacher',
            'location',
            'week_start',
            'week_end',
            'section_start',
            'section_end',
            'week_day',
            'week_type'
        ]);
        $data['term_id'] = $term_id;
        $course = (Course::firstOrCreate($data));
        if(!$course){
            return ['code' => 0, 'msg' => ' 修改失败'];
        }

        if($course->id != $request['id']){
            $user->courses()->detach($request['id']);
            $user->courses()->attach($course->id);
        }

        return ['code' => 1, 'msg' => '修改成功'];
        //返回
    }

    public function deleteCourse(){

//        $this->validate(request(),[
//            'user-id' => 'required|numeric|exists:users,id',
//            'id' => 'required|numeric|exists:courses,id',
//        ]);

        $user = Teacher::where('id', request('user_id'))->first();


        $user->courses()->detach(request('id'));
        return ['code' => 1, 'msg' => '删除成功'];
    }

    public function getTeacherNames(){
        $data = Teacher::orderBy('name_py')->get(['id','name']);
        return $data;
    }


}
