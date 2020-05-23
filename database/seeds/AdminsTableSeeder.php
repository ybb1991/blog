<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admin::create([
        //     'name'  => 'admin',
        //     'phone' => '17358483072',
        //     'email' => '12345678@qq.com',
        //     'password'  => bcrypt('483072'),
        // ]);

        // // 获取 Faker 实例
        $faker = app(Faker\Generator::class);
        $admins = factory(App\Models\Admin::class, 5)->make();
dd($admins);
        Admin::insert($admins->toArray());
    }
}
