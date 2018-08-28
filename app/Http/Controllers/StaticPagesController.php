<?php

namespace App\Http\Controllers; //命名空间

use Illuminate\Http\Request; //引用其他空间的类元素
use App\User;
use App\Status;
use Auth;

class StaticPagesController extends Controller //创建 静态页面控制器 并继承 Controller 类
{
    public function home() 
    {
        $feedItems = [];
        if(Auth::check()) {
            $feedItems = Auth::user()->feed()->paginate(30);
        }

        return view('staticpages.home', compact('feedItems'));
    }

    public function help() 
    {
        return view('staticpages.help');
    }

    public function about() 
    {
        return view('staticpages.about');
    }
}
