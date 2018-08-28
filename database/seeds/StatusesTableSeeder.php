<?php

use Illuminate\Database\Seeder;
use App\Status; //引用模型

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Status::class)->times(50)->create(); //创建50条数据
    }
}
