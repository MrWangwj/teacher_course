<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    //人员登录
    function login(){
        $this->validate(\request(), [
            'account' => 'required',
            'password' => 'required',
            'validate' => 'required|captcha',
        ]);
        $user = Admin::where('account', \request('account'))->where('password', md5(md5(\request('password'))))->first();
        if($user){
            session(['admin' => $user]);
            return $this->returnJSON(1, 'success');
        }

        return $this->returnJSON(env('DB_CONNECTION', 'mysql'), '账号或密码错误');
    }

    function logout(Request $request){
        $request->session()->forget('admin');
        return redirect('/admin/login');
    }
}
