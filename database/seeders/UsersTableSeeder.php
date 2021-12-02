<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'          => 'system',
            'loginId'       => 'System',
            'password'      => Hash::make('System'),
            'role'          => 0,
            'remember_token'=> '',
            'created_at'    => now(),
            'updated_at'    => now()
        ]);

        User::create([
            'name'          => 'administrator',
            'loginId'       => 'Admin',
            'password'      => Hash::make('Admin'),
            'role'          => 5,
            'remember_token' => '',
            'created_at'    => now(),
            'updated_at'    => now()
        ]);

        User::create([
            'name'          => 'user',
            'loginId'       => 'User',
            'password'      => Hash::make('User'),
            'role'          => 10,
            'remember_token' => '',
            'created_at'    => now(),
            'updated_at'    => now()
        ]);


    }
}
