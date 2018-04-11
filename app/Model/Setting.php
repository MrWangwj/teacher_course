<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    //返回当前周
    public static function getNowWeek(){
        $term_id = self::getNowTermId();
        $startSchool = strtotime(Term::where('id', $term_id)->first()->start_school);
        $nowTime = time();
        $week = ($nowTime - $startSchool) / (86400*7);
        return ceil($week) < 1 ? 1 : ceil($week);
    }

    public static function getNowTermId(){
        $termId = intval(self::where('key', 'nowTermId')->first()->value);
        return $termId;
    }
}
