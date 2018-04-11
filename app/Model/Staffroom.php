<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Staffroom extends Model
{
    const OTHER_ID = 12;

    //关联教师表
    public function teachers(){
        return $this->hasMany(Teacher::class, 'staffroom_id', 'id');
    }
}
