<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\category;

class category_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        category::create([
            'name'=>'برمجة'
        ]);
        category::create([
            'name'=>'تصميم'
        ]);
        category::create([
            'name'=>'علوم شرعية'
        ]);
    }
}
