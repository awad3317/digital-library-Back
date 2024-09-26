<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class user_seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'=>'superadmin',
            'password'=>'admin',
            'username'=>'superadmin',
            'user_type_id'=>'3',
            'active'=>true,
        ]);
        User::create([
            'name'=>'admin',
            'password'=>'admin',
            'username'=>'admin',
            'user_type_id'=>'1',
            'active'=>true,
        ]);
    }
}
