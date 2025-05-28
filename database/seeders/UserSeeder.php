<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /***********************
         *** Import Initial Users
         **********************/
        echo "Start loading Users...\n";
        // Add admin user
        $users = [
            [
                'role' => 'admin',
                'username' => 'admin',
                'email' => 'admin@utsouthwestern.edu',
                'password' => Hash::make(''),
                'permission' => 'all',
                'created_at' => now(),
            ],
            [
                'role' => 'admin',
                'username' => 'Tao',
                'email' => 'tao.wang@utsouthwestern.edu',
                'password' => Hash::make(''),
                'permission' => 'all',
                'created_at' => now(),
            ],
            [
                'role' => 'editor',
                'username' => 'Yuqiu',
                'email' => 'yuqiu.yang@utsouthwestern.edu',
                'password' => Hash::make(''),
                'permission' => 'all',
                'created_at' => now(),
            ],
            [
                'role' => 'editor',
                'username' => 'Yi',
                'email' => 'yi.han@utsouthwestern.edu',
                'password' => Hash::make(''),
                'permission' => 'all',
                'created_at' => now(),
            ],
            [
                'role' => 'editor',
                'username' => 'reviewer01',
                'email' => 'reviewer01@utsouthwestern.edu',
                'password' => Hash::make(''),
                'permission' => 'all',
                'created_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
