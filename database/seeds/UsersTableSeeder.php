<?php

use Illuminate\Database\Seeder;
use App\User; // 引用模型

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->times(50)->create(); //使用模型工厂创建50条假数据

        // 找到第一条设置成自己的信息
        $user = User::find(1);
        $user->name = 'woshimiexiaoming';
        $user->email = 'prohorry@outlook.com';
        $user->password = bcrypt('woshimiexiaoming');
        $user->is_admin = true;
        $user->activated = true;
        $user->save();
    }
}
