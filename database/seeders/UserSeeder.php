<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run()
    {
        // $users = [
        //     ['Nguyễn Văn A', 'a@example.com', 'staff'],
        //     ['Trần Thị B', 'b@example.com', 'user'],
        //     ['Lê Hữu C', 'c@example.com', 'admin'],
        //     ['Phạm Minh D', 'd@example.com', 'user'],
        //     ['Đặng Hoàng E', 'danghoange@example.com', 'staff'],
        //     ['Bùi Văn F', 'buivanf@example.com', 'admin'],
        //     ['Hoàng Gia G', 'hoanggiag@example.com', 'admin'],
        //     ['Võ Ngọc H', 'vongoch@example.com', 'admin'],
        //     ['Đỗ Kim I', 'dokimi@example.com', 'admin'],
        //     ['Tạ Quang J', 'taquangj@example.com', 'admin'],
        // ];

        // foreach ($users as $user) {
        //     User::create([
        //         'name' => $user[0],
        //         'email' => $user[1],
        //         'password' => Hash::make('12345'),
        //         'role' => $user[2],
        //         'status' => 'active',
        //         'avatar' => 'default-avatar.png',
        //     ]);
        // }

        DB::table("users")->insert([
            "name"=> "admin",
            "email"=> "admin@gmail.com",
            "password"=> Hash::make("12345"),
            "role"=> "admin",
            "status"=> "active",
            "phone"=> "0123456789",
            "address"=> "123 Main St, City, Country",
            "avatar"=> "default-avatar.png",
            "created_at" => now(),
            "updated_at" => now(),
        ]);
    }
}
