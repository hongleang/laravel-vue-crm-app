<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::factory(50)->create([
            'creator_id' => User::role(RolesEnum::Admin->value)->first()->id
        ]);
    }
}
