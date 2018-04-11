<?php

namespace App\Http\Controllers;

use App\Model\Node;

class SettingController extends Controller
{
    //
    function nodes(){
        $data = Node::select('id', 'name', 'code', 'path', 'icon')
            ->where('depth', 1)
            ->with('children')
            ->orderBy('sort_factor')
            ->get();
        return $data;
    }

}
