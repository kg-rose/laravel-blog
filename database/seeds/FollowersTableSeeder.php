<?php

use Illuminate\Database\Seeder;
use App\User; //引用 User 模型

class FollowersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 获取所有数据
        $users = User::all();
        // 获取第一个人的数据：我们用第一个人关注其他所有人，同时让其他所有人关注第一个人
        $user = $users->first();

        // 获取除了第一个人之外的其他所有用户
        $followers = $users->slice(1); //把1摘出去
        // 遍历 $follower 让每个 $userId 都去关注第一个人
        foreach($followers as $follower)
        {
            $follower->follow($user->id);
        }

        // pluck('某字段')： “只要该字段，其他都不要”， 然后 toArray() 转成数组
        $userIds = $followers->pluck('id')->toArray(); 
        // 让第一个人关注对所有人添加关注
        $user->follow($userIds);
    }
}
