<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Joblevel extends Model
{
    const OTHER_ID = 5;
    //关联教师表
    public function teachers(){
        return $this->hasMany(Teacher::class, 'joblevel_id', 'id');
    }
}
