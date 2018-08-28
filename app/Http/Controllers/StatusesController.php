<?php

namespace App\Http\Controllers;

use App\Status;
use Illuminate\Http\Request;
use Auth;

class StatusesController extends Controller
{
    /**
     * 必须登陆
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 验证数据
        $status = $this->validate($request, [
            'content' => 'required|min:15|max:255'
        ]);

        // 创建微博
        Auth::user()->statuses()->create($status);

        // 发送提示消息
        session()->flash('success', '发布动态成功');

        // 返回
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Status  $status
     * @return \Illuminate\Http\Response
     */
    public function destroy(Status $status)
    {
        // 授权
        $this->authorize('destroy', $status);

        // 删除
        $status->delete();

        // 发送闪存消息
        session()->flash('success', '删除微博成功');

        // 返回
        return redirect()->back();
    }
}
