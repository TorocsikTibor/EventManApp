<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin Doe',
            'email' => 'admin@test.com',
            'password' => Hash::make('123'),
            'is_admin' => 1,
        ]);

        DB::table('users')->insert([
            'name' => 'John Doe',
            'email' => 'jhondoe@test.com',
            'password' => Hash::make('123'),
            'is_admin' => 0,
        ]);

        DB::table('users')->insert([
            'name' => 'Jane Doe',
            'email' => 'janedoe@test.com',
            'password' => Hash::make('123'),
            'is_admin' => 0,
        ]);

        DB::table('users')->insert([
            'name' => 'Hát Izsák',
            'email' => 'hatizsak@test.com',
            'password' => Hash::make('123'),
            'is_admin' => 0,
        ]);
    }
}
