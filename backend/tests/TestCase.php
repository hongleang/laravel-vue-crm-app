<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $seeder = RoleAndPermissionSeeder::class;

    public function setUp(): void
    {
        parent::setUp();

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function createUser(array $attributes=[]): User
    {
        return User::factory()->active()->create($attributes);
    }

    public function createAdmin(array $attributes=[]): User
    {
        return User::factory()->admin()->create($attributes);
    }

    public function createManager(array $attributes=[]): User
    {
        return User::factory()->manager()->create($attributes);
    }

    public function createSales(array $attributes=[]): User
    {
        return User::factory()->sales()->create($attributes);
    }
}
