<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
       $users = [
            [
                'name' => 'Admin Master',
                'email' => 'admin@gmail.com',
                'phone' => '0909123456',
                'address' => 'Hà Nội, Việt Nam',
                'role' => 'admin',
                'status' => 'active',
                'avatar' => 'avatars/admin.png',
                'password' => Hash::make('admin123'),
            ],
            [
                'name' => 'Nguyễn Vân',
                'email' => 'van@gmail.com',
                'phone' => '0988765432',
                'address' => 'Tp. Hồ Chí Minh',
                'role' => 'user',
                'status' => 'active',
                'avatar' => 'avatars/user1.png',
                'password' => Hash::make('12345678'),
            ],
            [
                'name' => 'Lê Thị Xuân',
                'email' => 'ltxuan@gmail.com',
                'phone' => '0912123456',
                'address' => 'Đà Nẵng',
                'role' => 'user',
                'status' => 'inactive',
                'avatar' => 'avatars/user2.jpg',
                'password' => Hash::make('12345678'),
            ]
        ];

        foreach ($users as $data) {
            $data['remember_token'] = Str::random(10);
            $data['email_verified_at'] = now();
            $data['verification_code'] = Str::upper(Str::random(6));
            User::create($data);
        }
    }
}
