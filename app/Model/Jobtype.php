<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Jobtype extends Model
{
    const OTHER_ID = 6;
    //关联教师表
    public function teachers(){
        return $this->hasMany(Teacher::class, 'jobtype_id', 'id');
    }
}
