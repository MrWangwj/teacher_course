<?php

namespace App\Http\Controllers\Admin;

use App\Model\Staffroom;
use App\Model\Teacher;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class StaffroomController extends Controller
{
    //
    public function staffrooms(){
        $data = Staffroom::with(['teachers'=>function($query){
            return $query->select(['id', 'name', 'staffroom_id']);
        }])->orderBy('sort')->get(['id','name']);
        return $data;
    }

    public function create(){
        $this->validate(\request(), [
            'name' => 'required'
        ]);

        $name = \request('name');

        $staffrooms = Staffroom::get(['id', 'name']);
        foreach ($staffrooms as $v){
            if($v->name == request('name'))
                return $this->returnJSON(0, '名称重复');
        }

        $staffroom = new Staffroom();
        $staffroom->name = $name;
        $staffroom->sort = count($staffrooms)+1;
        $result = $staffroom->save();
        if($result)
            return $this->returnJSON(1, '添加成功');
        return $this->returnJSON(0, '添加失败');
    }

    public function edit(){
        $this->validate(\request(), [
            'id' => 'required|numeric',
            'name' => 'required',
            'teachers' => 'required|array',

        ]);

        DB::beginTransaction();
        $result1 = Teacher::where('staffroom_id', \request('id'))->update(['staffroom_id' => Staffroom::OTHER_ID]);
        $result2 = Teacher::whereIn('id', \request('teachers'))->update(['staffroom_id' => \request('id')]);
        $result3 = Staffroom::where('id', \request('id'))->update(['name' => \request('name')]);

        if($result1 >= 0 && $result2 >= 0 && $result3 >= 0){
            DB::commit();
            return $this->returnJSON(1,'修改成功');
        }else{
            DB::rollBack();
            return $this->returnJSON(0, '修改失败');
        }
    }

    public function delete(){
        $this->validate(\request(), [
            'id' => 'required|numeric'
        ]);

        if(\request('id') == Staffroom::OTHER_ID)
            return $this->returnJSON(0, '不能删除默认分组');

        DB::beginTransaction();
        $result1 = Teacher::where('staffroom_id', \request('id'))->update(['staffroom_id' => Staffroom::OTHER_ID]);
        $staffroom = Staffroom::where('id', \request('id'))->first();
        $result2 = null;
        if($staffroom){
            $result2 = Staffroom::where('sort', '>', $staffroom->sort)->decrement('sort');
            $staffroom->delete();
        }

        if($result1 >= 0 && $staffroom && $result2 >= 0){
            DB::commit();
            return $this->returnJSON(1,'删除成功');
        }else{
            DB::rollBack();
            return $this->returnJSON(0, '删除失败');
        }
    }

    public function sorting(){
         $this->validate(request(), [
             'id1' => 'required|exists:staffrooms,id',
             'id2' => 'required|exists:staffrooms,id',
         ]);

         $data = Staffroom::where('id', request('id1'))->orWhere('id', request('id2'))->get()->all();
         if(count($data) != 2)
             return $this->returnJSON(0, '发生错误');
         DB::beginTransaction();
         $sort = $data[0]->sort;
         $data[0]->sort = $data[1]->sort;
         $data[1]->sort = $sort;
         $result1 = $data[0]->save();
         $result2 = $data[1]->save();
         if($result1 && $result2){
             DB::commit();
             return $this->returnJSON(1, '修改成功');
         }

         DB::rollback();
         return $this->returnJSON(0, '修改失败');
    }
}
