<?php

namespace App;

use Illuminate\Notifications\Notifiable; //消息通知功能
use Illuminate\Foundation\Auth\User as Authenticatable; //授权相关功能
use App\Notifications\ResetPassword; //引用消息通知类
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    // 指定模型映射的数据表，默认：模型首字母大写单数，表名全小写复数，那么这段代码其实可以不要
    protected $table = 'users';

    // 可填字段白名单
    protected $fillable = [
        'name', 'email', 'password',
    ];

    // 隐藏字段：比如查询表中所有数据然后给前台发送过去，前台拿到的数据中这些字段是不会显示的
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Gravator头像
    public function gravatar($size = '100')
    {
        $hash = md5(strtolower(trim($this->email)));
        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }

    // 激活令牌生成
    public static function boot()
    {
        parent::boot();

        static::creating(function($user) {
            $user->activation_token = str_random(32);
        });
    }

    // 发送密码重置验证码
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * 关联 User 1:n Status 关系
     */
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    /**
     * 展示新发布微博和关注的人的微博
     */
    public function feed()
    {
        // 获取关注的人的 id数组
        $userIds = Auth::user()->followings->pluck('id')->toArray();
        // 把自己也放进去（因为也要显示自己最新的）
        array_push($userIds, Auth::user()->id);

        // 使用 whereIn 查询，orderBy 根据创建时间倒序排序
        return Status::whereIn('user_id', $userIds)
            ->with('user')
            ->orderBy('created_at', 'desc');
    }

    /**
     * 多对多关系绑定
     */
    public function followers() //我的粉丝
    {
        // belongsToMany(关联的模型类, '关联数据表名', '关联模型外键', '合并模型外键')
        return $this->belongsToMany(User::class, 'followers', 'user_id', 'follower_id');
    }
    public function followings() //关注的人
    {
        return $this->belongsToMany(User::Class, 'followers', 'follower_id', 'user_id');
    }

    /**
     * 关注和取消关注
     */
    public function follow($userIds) //关注
    {
        if(!is_array($userIds)) {
            $userIds = compact('userIds');
        }

        $this->followings()->sync($userIds, false);
    }
    public function unFollow($userIds) //取消关注
    {
        if(!is_array($userIds)) {
            $userIds = compact('userIds');
        }

        $this->followings()->detach($userIds);
    }

    /**
     * 判断是否关注
     */
    public function isFollowing($userId)
    {
        return $this->followings->contains($userId);
    }
}
