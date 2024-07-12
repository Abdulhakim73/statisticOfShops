<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    public function run(): void
    {
        //user
        DB::table('users')->insert([
            'name' => 'Karimov Hakimjon',
            'role_id' => 1,
            'phone' => '998977731573',
            'status' => 'active',
            'password' => Hash::make('123456'),
            'type' => 'admin',
        ]);

        DB::table('users')->insert([
            'name' => 'Prof. Lamont Haley MD',
            'role_id' => 2,
            'phone' => '998977226902',
            'status' => 'active',
            'password' => Hash::make('123456'),
            'type' => 'user',
        ]);

        DB::table('users')->insert([
            'name' => 'Hailee Kassulke',
            'role_id' => 2,
            'phone' => '998071193200',
            'status' => 'active',
            'password' => Hash::make('123456'),
            'type' => 'user',
        ]);

        DB::table('users')->insert([
            'name' => 'Zola Powlowski IV',
            'role_id' => 2,
            'phone' => '998062957426',
            'status' => 'active',
            'password' => Hash::make('123456'),
            'type' => 'user',
        ]);

        //user activation
        DB::table('user_activations')->insert([
            'user_id' => 1,
            'code' => '123',
            'exp_at' => '2024-05-15',
            'status' => 'complete',
            'phone' => '998977731573',
        ]);

        DB::table('user_activations')->insert([
            'user_id' => 2,
            'code' => '3554',
            'exp_at' => '2024-05-15',
            'status' => 'complete',
            'phone' => '998977226902',
        ]);

        DB::table('user_activations')->insert([
            'user_id' => 3,
            'code' => '5158',
            'exp_at' => '2024-05-15',
            'status' => 'complete',
            'phone' => '998071193200',
        ]);

        DB::table('user_activations')->insert([
            'user_id' => 4,
            'code' => '2346',
            'exp_at' => Carbon::now()->addDays(30),
            'status' => 'complete',
            'phone' => '998062957426',
        ]);
    }
}
