<?php

namespace App\Http\Controllers\Wechat;

use App\Model\Joblevel;
use App\Model\Jobtype;
use App\Model\Staffroom;
use App\Model\Title;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TeacherController extends Controller
{
    //
    public function types(){
        $joblevels = Joblevel::orderBy('id')->get(['id', 'name']);
        $jobtypes = Jobtype::orderBy('id')->get(['id', 'name']);
        $staffrooms = Staffroom::orderBy('id')->get(['id', 'name']);
        $titles = Title::orderBy('id')->get(['id', 'name']);
        return compact(['joblevels', 'jobtypes', 'staffrooms', 'titles']);
    }
}
