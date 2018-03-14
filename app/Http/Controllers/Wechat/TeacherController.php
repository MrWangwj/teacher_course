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
        $joblevels = Joblevel::orderBy('sort')->get(['id', 'name']);
        $jobtypes = Jobtype::orderBy('sort')->get(['id', 'name']);
        $staffrooms = Staffroom::orderBy('sort')->get(['id', 'name']);
        $titles = Title::orderBy('sort')->get(['id', 'name']);
        return compact(['joblevels', 'jobtypes', 'staffrooms', 'titles']);
    }
}
