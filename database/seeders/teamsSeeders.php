<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class teamsSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('teams')->insert([
            ['name' => 'محمد العمودي'],
            ['name' => 'فارس بن عجاج'],
            ['name' => 'عوض عبدالله لشرم'],
            ['name' => 'عبدالعزيز مبارك بريشان'],
            ['name' => 'عمر بارجاء'],
        ]);
    }
}
