<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Database\Seeders\factories\departmentFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\department;


class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('departments')->insert([
            ['name' => 'حاسوب'],
            ['name' => 'بترولي'],
            ['name' => 'معماري'],
            ['name' => 'كيميائي'],
            ['name' => 'الالكتروني'],
        ]);

    //     department::create([
    //        ['name'=>'computer'],
    //         'name'=>'civil',
    //         'name'=>'architectural',
    //         'name'=>'electronic',
    //         'name'=>'Chemical'

    //     ]);
    }

}
