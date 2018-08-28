<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Auth;
use Mail;

class UsersController extends Controller
{   
    /**
     * 要求用户必须登陆
     */
    public function __construct()
    {
        // auth 中间件：要求用户必须登陆才能操作
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'SendActivacionEmail', 'confirmEmail'] // 使用 except 排除这些操作
        ]);

        // guest 中间件： 要求用户必须没有登陆才能操作
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(10); //查询并分页，每页10条数据

        return view('users.index', compact('users')); //将 $users 传过去
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // 验证数据合法性
        $this->validate($request, [
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        // 数据入库
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        // 发送激活码邮件
        $this->SendActivacionEmail($user);

        // 存储临时会话消息
        session()->flash('success', '注册成功，请检查您的邮箱');

        // 重定向到主页
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {   
        // 调用所有微博
        $statuses = $user->statuses()
            ->orderBy('created_at', 'desc') //根据创建时间倒序排序 orderBy('字段', 'asc | desc')
            ->paginate(30); //分页，每页30条数据

        // view('视图', compact('将参数列表中通过依赖注入生成的实例$user转为关联数组传递给视图'));
        return view('users.show', compact('user', 'statuses')); //打包数据给视图时再加上 statuses
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {  
        // 授权
        $this->authorize('update', $user);

        // 跳转并带参数
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        // 校验数据
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);
        
        // 授权
        $this->authorize('update', $user);

        // 拼装数据
        $data = [];
        $data['name'] = $request->name;
        if($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        // 更新
        $user->update($data);

        // 闪存flash
        session()->flash('success', '更新资料成功');

        // 重定向到个人信息
        return redirect()->route('users.show', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // 授权
        $this->authorize('delete', $user);

        // 删除
        $user->delete();

        // 发送闪存消息
        session()->flash('success', '成功删除！');

        // 重定向跳转回上一页
        return redirect()->back();
    }

    /**
     * 邮件发送
     */
    public function SendActivacionEmail($user)
    {
        $view = 'emails.confirm'; //配置视图
        $data = compact('user'); //绑定数据
        // $from = 'prohorry@163.com'; //发件人邮箱地址
        // $name = 'prohorry'; //发件人姓名
        $to = $user->email; //收件人邮箱地址
        $subject = "欢迎注册 haoweibo ！ 请确认您的邮箱地址。"; //主题（邮件标题）

        Mail::send($view, $data, function($message) use ($to, $subject) {
            $message
            // ->from($from, $name)
            ->to($to)
            ->subject($subject);
        });
    }

    /**
     * 账号激活
     */
    public function confirmEmail($id, $token)
    {
        $user = User::find($id); //根据id找到用户

        // 匹配 token ，确认激活，更新数据
        if($user->activation_token == $token) { 
            $user->activated = true;
            $user->activation_token = null;
            $user->save();
            
            // 自动登陆，发送提示，重定向
            Auth::login($user);
            session()->flash('success', '恭喜你，激活成功！');
            return redirect()->route('users.show', [$user]);
        } else {
            session()->flash('danger', '激活失败。请再次点击邮件中的链接重试');
            return redirect('/');
        }

    }

    /**
     * 关注的人 和 我的粉丝
     * 这里共用一个视图，只需要查不同的数据，用一个变量名
     * 然后传不同的title即可
     */
    public function followings(User $user)
    {
        $users = $user->followings()->paginate(30);
        $title = '关注的人';

        return view('users.follow', compact('users', 'title'));
    }
    public function followers(User $user) 
    {
        $users = $user->followers()->paginate(30);
        $title = '我的粉丝';

        return view('users.follow', compact('users', 'title'));
    }
}
