<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Title extends Model
{
    const OTHER_ID = 8;
    //关联教师表
    public function teachers(){
        return $this->hasMany(Teacher::class, 'title_id', 'id');
    }
}
