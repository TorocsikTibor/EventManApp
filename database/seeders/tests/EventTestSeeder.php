<?php

namespace Database\Seeders\tests;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EventTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('events')->insert([
            'name' => 'test',
            'owner_id' => 1,
            'date' => '2023-10-31 20:00:00',
            'location' => "Hungary",
            'image' => "20231030195408new.png",
            'type' => 'concert',
            'description' => 'test desc',
            'is_private' => 0,
        ]);
    }
}
