<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class academic_yearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('academic_years')->insert([
            ['year' => '2019'],
            ['year' => '2020'],
            ['year' => '2021'],
            ['year' => '2022'],
            ['year' => '2023'],
        ]);
        // $years = [];

    // for ($i = 1; $i < 24; $i++) {
    //     $year = [
    //         'year' => 2000 + $i
    //     ];

    //     $years[] = $year;
    // }

    // DB::table('academic_years')->insert($years);
    }
}
