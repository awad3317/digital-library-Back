<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            category_seeder::class,
            type_user_seeder::class,
            DepartmentSeeder::class,
            teamsSeeders::class,
            user_seeder::class,
            academic_yearSeeder::class,
            subject_seeder::class
        ]);
    }
}
