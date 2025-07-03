<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run()
    {
        $users = [
          
            [
                'name' => 'A B C',
                'email' => 'abc@gmail.com',
                'phone' => '0912123456',
                'address' => 'Đà Nẵng',
                'role' => 'user',
                'status' => 'active',
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
