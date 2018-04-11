<?php

namespace App\Http\Controllers\Admin;

use App\Model\Setting;
use App\Model\Term;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use function Symfony\Component\Debug\Tests\testHeader;

class TermController extends Controller
{
    //
    public function terms(){
        $data = Term::orderBy('start_school', 'desc')->get(['id', 'name', 'start_school']);
        $nowTermId =  Setting::getNowTermId();
        return compact(['data', 'nowTermId']);
    }

    public function setNowTerm(){
        $this->validate(\request(), [
            'id' => 'required|exists:terms,id'
        ]);

        $setting = Setting::where('key', 'nowTermId')->first();
        $setting->value = \request('id');
        $result = $setting->save();
        if($result)
            return $this->returnJSON(1, '设置成功');
        return $this->returnJSON(0, '设置失败');
    }

    public function edit(){
        $this->validate(\request(), [
            'name' => 'required',
            'id' => 'required',
            'start_school' => 'required|date'
        ]);

        if(\request('id') == Term::DEFAULT_TERM)
            return $this->returnJSON(0, '默认学期不能编辑');

        $result = Term::where('id', \request('id'))->update(['name' => \request('name'), 'start_school' => date('Y-m-d',strtotime(\request('start_school')))]);
        if($result)
            return $this->returnJSON(1, '修改成功');
        return $this->returnJSON(0, '修改失败');
    }

    public function create(){
        $this->validate(\request(), [
            'name' => 'required',
            'start_school' => 'required|date'
        ]);

        $term = Term::where('name', \request('name'))->first();
        if($term)
            return $this->returnJSON(0, '名称重复');
        $term = new Term();
        $term->name = \request('name');
        $term->start_school = date('Y-m-d',strtotime(\request('start_school')));
        $result = $term->save();
        if($result)
            return $this->returnJSON(1, '添加成功');
        return $this->returnJSON(0, '添加失败');
    }

    public function delete(){
        $this->validate(\request(), [
            'id' => 'required'
        ]);
        if(\request('id') == Term::DEFAULT_TERM)
            return $this->returnJSON(0, '不能删除默认学期');
        $now_term_id = Setting::getNowTermId();
        if(\request('id') == $now_term_id)
            return $this->returnJSON(0, '不能删除当前学期');

        $result = Term::where('id', \request('id'))->delete();
        if($result)
            return $this->returnJSON(1, '删除成功');
        return $this->returnJSON(0, '删除失败');
    }

}
