<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Term extends Model
{
    //
    const DEFAULT_TERM = 1;

    /*
     * 判断学期
     * */
    static function judgeIterm(){
        $months = date('m',time());
        if ($months < 8){
            $kind = 1;
            $year = date('Y',time());
            $year = $year-1;
            return $year.$kind;
        }else{
            $kind = 0;
            $year = date('Y',time());
            return $year.$kind;
        }
    }

}
