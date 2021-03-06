<?php

namespace App\Http\Controllers\Admin;

use App\Model\Jobtype;
use App\Model\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class JobtypeController extends Controller
{
    public function jobtypes(){
        $data = Jobtype::with(['teachers'=>function($query){
            return $query->select(['id', 'name', 'jobtype_id']);
        }])->orderBy('sort')->get(['id','name']);
        return $data;
    }

    public function create(){
        $this->validate(\request(), [
            'name' => 'required'
        ]);

        $name = \request('name');

        $data = Jobtype::get(['id', 'name']);
        foreach ($data as $v){
            if($v->name == request('name'))
                return $this->returnJSON(0, '名称重复');
        }

        $obj = new Jobtype();
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

        $result1 = Teacher::where('jobtype_id', \request('id'))->update(['jobtype_id' => Jobtype::OTHER_ID]);
        $result2 = Teacher::whereIn('id', \request('teachers'))->update(['jobtype_id' => \request('id')]);
        $result3 = Jobtype::where('id', \request('id'))->update(['name' => \request('name')]);

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

        if(\request('id') == Jobtype::OTHER_ID)
            return $this->returnJSON(0, '不能删除默认分组');

        DB::beginTransaction();
        $result1 = Teacher::where('jobtype_id', \request('id'))->update(['jobtype_id' => Jobtype::OTHER_ID]);
        $data = Jobtype::where('id', \request('id'))->first();
        $result2 = null;
        if($data){
            $result2 = Jobtype::where('sort', '>', $data->sort)->decrement('sort');
            $data->delete();
        }

        if($result1 >= 0 && $data && $result2 >= 0){
            DB::commit();
            return $this->returnJSON(1,'删除成功');
        }else{
            DB::rollBack();
            return $this->returnJSON(0, '删除失败',[$result1, $data, $result2]);
        }
    }

    public function sorting(){
        $this->validate(request(), [
            'id1' => 'required|exists:jobtypes,id',
            'id2' => 'required|exists:jobtypes,id',
        ]);

        $data = Jobtype::where('id', request('id1'))->orWhere('id', request('id2'))->get()->all();
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
