<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Course extends Model
{
    //
    //不可被批量复制的字段
    public $guarded = ['id'];

    //关联课表
    public function courses(){
        return $this->belongsToMany(Teacher::class, 'teacher_course', 'course_id', 'teacher_id');
    }

    /*
     * 出去非当前学期的课程
     * */
    static function romoveCourse($id){
        DB::beginTransaction();
        try{
            $course_id = Teacher::find($id)->courses()->get();
            Teacher::find($id)->courses()->detach();
            for ($i = 0 ; $i < count($course_id) ;$i++ ){
                $course[$i] = $course_id[$i]->id;
            }
            Course::destroy($course);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollback();
        }
    }
}
