<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class PrayersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('prayers')->insert([
            [
                'name' => 'Fajr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Dhuhr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Asr',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Maghrib',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Isha',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
