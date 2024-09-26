<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\subject;

class subject_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        subject::create([
            'name' => 'قواعد بيانات',
            'semester'=>'5',
        ]);
        subject::create([
            'name' => 'رياضيات 2',
            'semester'=>'2',
        ]);
        subject::create([
            'name' => 'مقدمة برمجه',
            'semester'=>'1',
        ]);
        subject::create([
            'name' =>'شبكات 1',
            'semester'=>'7',
        ]);
        subject::create([
            'name' => 'هياكل بيانات',
            'semester'=>'3',
        ]);
    }
}
