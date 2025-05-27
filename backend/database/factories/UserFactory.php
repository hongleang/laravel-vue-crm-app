<?php

namespace Database\Factories;

use App\Enums\UserStatusEnum;
use App\Enums\RolesEnum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'mobile' => fake()->phoneNumber(),
            'password' => static::$password ??= Hash::make('Secret*12345'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => UserStatusEnum::Enabled,
        ]);
    }

    public function admin(): static
    {
        return $this->active()->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::Admin);
        });
    }

    public function manager(): static
    {
        return $this->active()->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::Manager);
        });
    }

    public function sales(): static
    {
        return $this->active()->afterCreating(function (User $user) {
            $user->assignRole(RolesEnum::Sales);
        });
    }
}
