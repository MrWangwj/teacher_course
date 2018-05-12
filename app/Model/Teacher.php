<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    //不可被批量复制的字段
    public $guarded = ['id'];

    //关联职务级别表
    public function joblevel(){
        return $this->belongsTo(Joblevel::class, 'joblevel_id', 'id');
    }

    //关联职务类型表
    public function jobtype(){
        return $this->belongsTo(Jobtype::class, 'jobtype_id', 'id');
    }

    //关联教研室表
    public function staffroom(){
        return $this->belongsTo(Staffroom::class, 'staffroom_id', 'id');
    }

    //关联职称表
    public function title(){
        return $this->belongsTo(Title::class, 'title_id', 'id');
    }

    //关联课表
    public function courses(){
        return $this->belongsToMany(Course::class, 'teacher_course', 'teacher_id', 'course_id');
    }
}
