<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->admin()->create([
            'email' => 'admin@crmapp.com'
        ]);

        $manager = User::factory()->manager()->create([
            'email' => 'manager@crmapp.com'
        ]);

        $user = User::factory()->active()->create([
            'email' => 'user@crmapp.com'
        ]);

        $activeUsers = User::factory(10)->active()->create();

        User::factory(10)->create();
    }
}
