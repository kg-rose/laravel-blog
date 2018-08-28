<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\User; //引用 User 模型
use Auth; //引用 Auth

class FollowersController extends Controller
{
    // 登陆校验
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 添加关注
    public function store(User $user)
    {
        // 判断当前用户是不是自己
        if(Auth::user()->id == $user->id)
        {
            return redirect('/');
        }

        // 判断是否关注了对方，没关注则关注
        if(!Auth::user()->isFollowing($user->id)) {
            Auth::user()->follow($user->id);
        }

        // 重定向
        return redirect()->route('users.show', $user);
    }

    // 取消关注
    public function destroy(User $user)
    {
        // 判断当前用户是不是自己
        if(Auth::user()->id == $user->id)
        {
            return redirect('/');
        }

        // 判断是否关注了对方，关注则取消关注
        if(Auth::user()->isFollowing($user->id)) {
            Auth::user()->unfollow($user->id);
        }

        // 重定向
        return redirect()->route('users.show', $user);
    }
}
