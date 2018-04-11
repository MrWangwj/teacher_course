<?php

namespace App\Http\Controllers\Admin;

use App\Model\Joblevel;
use App\Model\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class JoblevelController extends Controller
{
    public function joblevels(){
        $data = Joblevel::with(['teachers'=>function($query){
            return $query->select(['id', 'name', 'joblevel_id']);
        }])->orderBy('sort')->get(['id','name']);
        return $data;
    }

    public function create(){
        $this->validate(\request(), [
            'name' => 'required'
        ]);

        $name = \request('name');

        $data = Joblevel::get(['id', 'name']);
        foreach ($data as $v){
            if($v->name == request('name'))
                return $this->returnJSON(0, '名称重复');
        }

        $obj = new Joblevel();
        $obj->name = $name;
        $obj->sort = count($data)+1;
        $result = $obj->save();
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

        $result1 = Teacher::where('joblevel_id', \request('id'))->update(['joblevel_id' => Joblevel::OTHER_ID]);
        $result2 = Teacher::whereIn('id', \request('teachers'))->update(['joblevel_id' => \request('id')]);
        $result3 = Joblevel::where('id', \request('id'))->update(['name' => \request('name')]);

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

        if(\request('id') == Joblevel::OTHER_ID)
            return $this->returnJSON(0, '不能删除默认分组');

        DB::beginTransaction();
        $result1 = Teacher::where('joblevel_id', \request('id'))->update(['joblevel_id' => Joblevel::OTHER_ID]);
        $data = Joblevel::where('id', \request('id'))->first();
        $result2 = null;
        if($data){
            $result2 = Joblevel::where('sort', '>', $data->sort)->decrement('sort');
            $data->delete();
        }

        if($result1 >= 0 && $data && $result2 >= 0){
            DB::commit();
            return $this->returnJSON(1,'删除成功');
        }else{
            DB::rollBack();
            return $this->returnJSON(0, '删除失败');
        }
    }

    public function sorting(){
        $this->validate(request(), [
            'id1' => 'required|exists:joblevels,id',
            'id2' => 'required|exists:joblevels,id',
        ]);

        $data = Joblevel::where('id', request('id1'))->orWhere('id', request('id2'))->get()->all();
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
