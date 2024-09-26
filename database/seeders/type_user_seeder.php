<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\user_type;

class type_user_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        user_type::create([
            'type'=>'ادمن'
        ]);
        user_type::create([
            'type'=>'طاقم تعلمي'
        ]);
        user_type::create([
            'type'=>' سوبر ادمن'
        ]);
    }
}
