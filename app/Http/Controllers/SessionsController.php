<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth; //引用 Auth

class SessionsController extends Controller
{
    /**
     * 构造函数
     */
    public function __construct()
    {
        // guest 中间件： 要求用户必须没有登陆才能操作
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * 登陆
     */
    public function create() {
        return view('sessions.create');
    }

    /**
     * 登陆认证
     */
    public function store(Request $request) {
        // 校验数据合法性
        $user = $this->validate($request, [
            'email'=> 'required|email|max:255',
            'password' => 'required' 
        ]); //这里校验的同时用 $user 存储表单信息
        
        // Auth:;attempt(邮箱密码数组) 实现登陆
        if(Auth::attempt($user, $request->has('remember_me'))) {
            // 检查是否激活
            if(Auth::user()->activated) {
                session()->flash('success', '欢迎回来!');
                return redirect()->intended(route('users.show', [Auth::user()]));
            }else {
                Auth::logout();
                session()->flash('warning', '您的账号未激活，请检查邮箱');
                return redirect('/');
            }
        }else {
            session()->flash('danger', '邮箱或密码错误!');
            return redirect()->back();
        }
    }

    /**
     * 登出
     */
    public function destroy() {
        Auth::logout();
        session()->flash('success', '您已成功退出');
        return redirect('login');
    }
}
