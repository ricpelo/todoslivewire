<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        DB::table('todos')->insert([
            [
                'title' => 'First Todo',
                'description' => 'This is the first todo item.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Second Todo',
                'description' => 'This is the second todo item.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Third Todo',
                'description' => 'This is the third todo item.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Fourth Todo',
                'description' => 'This is the fourth todo item.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
