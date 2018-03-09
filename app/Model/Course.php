<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    //
    //不可被批量复制的字段
    public $guarded = ['id'];

    //关联课表
    public function courses(){
        return $this->belongsToMany(Teacher::class, 'teacher_course', 'course_id', 'teacher_id');
    }
}
