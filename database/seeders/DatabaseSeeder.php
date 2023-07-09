<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $now = \Carbon\Carbon::now();
        Role::insert([
            [
                'name' => 'admin',
                'description' => 'Privileged user with full control, responsible for managing accounts, settings, and security of the e-learning platform.',
                'created_at' => $now
            ],
            [
                'name' => 'user',
                'description' => 'Regular learner with access to courses, learning materials, and progress tracking on the e-learning platform.',
                'created_at' => $now
            ],
            [
                'name' => 'creator',
                'description' => 'User with content creation privileges, responsible for developing and publishing educational materials on the e-learning platform.',
                'created_at' => $now
            ],
        ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
