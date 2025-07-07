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
                'name' => 'abe',
                'email' => 'a@gmail.com',
                'phone' => '0912123456',
                'address' => 'Đà Nẵng',
                'role' => 'user',
                'status' => 'active',
                'avatar' => 'uploads/users/1751792301_686a3aadaa5a0.jpg',
                'password' => Hash::make('123456'),
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
