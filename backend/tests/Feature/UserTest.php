<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function testUnauthenticatedUserCannotAccessUserRoutes()
    {
        User::factory()->create();

        $this->getJson('/api/users')
            ->assertUnauthorized();
    }

    public function testCannotListUsersWithoutPermission()
    {
        $this->actingAs(User::factory()->create())
            ->getJson('/api/users')
            ->assertForbidden();
    }

    public function testCanListUsers()
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->getJson('/api/users')
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($admin) {
                $json->hasAll(['data', 'meta', 'links'])
                    ->has('data', 1, function (AssertableJson $json) use ($admin) {
                        $json
                            ->where('name', $admin->name)
                            ->where('email', $admin->email)
                            ->where('mobile', $admin->mobile)
                            ->has('created_at')
                            ->etc();
                    });
            });
    }

    public function testSearchUserByNameReturnsMatchingResults()
    {
        $admin = $this->createAdmin([
            'first_name' => 'AAA'
        ]);

        [$userA, $userB] = User::factory(2)
            ->sequence(
                ['first_name' => 'Aaron'],
                ['first_name' => 'Bob', 'last_name' => 'Smith'],
            )
            ->create();

        $response = $this->actingAs($admin)
            ->getJson('/api/users?' . http_build_query([
                'search' => 'aa'
            ]))
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($admin, $userA, $userB) {
                $json->hasAll(['data', 'meta', 'links'])
                    ->has('data', 2)
                    ->where('data.0.name', $admin->name)
                    ->where('data.1.name', $userA->name);
            });

        collect($response->json('data'))->each(function ($item) use ($userB) {
            $this->assertNotEquals($userB->name, $item['name']);
        });
    }

    public function testSortNameByOrder()
    {
        $admin = $this->createAdmin(['first_name' => 'Zach', 'last_name' => 'Brian'],);

        [$userA, $userB] = User::factory(2)
            ->sequence(
                ['first_name' => 'John', 'last_name' => 'Smith'],
                ['first_name' => 'Bob', 'last_name' => 'Smith'],
            )
            ->create();

        $this->actingAs($admin)
            ->getJson('/api/users?' . http_build_query([
                'sortBy' => 'asc'
            ]))
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($admin, $userA, $userB) {
                $json->hasAll(['data', 'meta', 'links'])
                    ->has('data', 3)
                    ->where('data.0.name', $userB->name)
                    ->where('data.1.name', $userA->name)
                    ->where('data.2.name', $admin->name);
            });

        $this->actingAs($admin)
            ->getJson('/api/users?' . http_build_query([
                'sortBy' => 'desc'
            ]))
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($admin, $userA, $userB) {
                $json->hasAll(['data', 'meta', 'links'])
                    ->has('data', 3)
                    ->where('data.0.name', $admin->name)
                    ->where('data.1.name', $userA->name)
                    ->where('data.2.name', $userB->name);
            });
    }

    public function testCannotViewSingleUserWithoutPermission()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->getJson('/api/users/' . $user->id)
            ->assertForbidden();
    }

    public function testCanViewSingleUser()
    {
        Carbon::setTestNow(Carbon::now());

        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->getJson('/api/users/' . $admin->id)
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($admin) {
                $json->has('data', function (AssertableJson $json) use ($admin) {
                    $json
                        ->where('name', $admin->name)
                        ->where('first_name', $admin->first_name)
                        ->where('last_name', $admin->last_name)
                        ->where('email', $admin->email)
                        ->where('mobile', $admin->mobile)
                        ->has('created_at')
                        ->etc();
                });
            });
    }

    public function testCannotCreateUserWithoutPermission()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->postJson('/api/users', [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'mobile' => '1234567890',
            ])
            ->assertForbidden();
    }

    public function testCanCreateUserWithValidData()
    {
        Carbon::setTestNow(Carbon::now());
        Event::fake();

        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->postJson('/api/users', [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'mobile' => '1234567890',
            ])
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function (AssertableJson $json) {
                    $json
                        ->where('name', 'John Doe')
                        ->where('email', 'john@example.com')
                        ->where('mobile', '1234567890')
                        ->has('created_at')
                        ->etc();
                });
            });

        Event::assertDispatched(Registered::class);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }

    public function testCannotCreateUserWithInvalidData()
    {
        Carbon::setTestNow(Carbon::now());

        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->postJson('/api/users', [
                'first_name' => Str::random(256),
                'last_name' => Str::random(256),
                'email' => Str::random(256),
                'mobile' => Str::random(256),
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'email',
                'mobile',
            ]);
    }

    public function testCannotUpdateUserWithoutPermission()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->putJson('/api/users' . '/' . $user->id, [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'mobile' => '1234567890',
            ])
            ->assertForbidden();
    }

    public function testCanUpdateUserWithValidData()
    {
        Carbon::setTestNow(Carbon::now());
        Event::fake();

        $admin = $this->createAdmin();

        $user = User::factory()->create();

        $this->actingAs($admin)
            ->putJson('/api/users' . '/' . $user->id, [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john@example.com',
                'mobile' => '1234567890',
            ])
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) {
                $json->has('data', function (AssertableJson $json) {
                    $json
                        ->where('name', 'John Doe')
                        ->where('email', 'john@example.com')
                        ->where('mobile', '1234567890')
                        ->has('created_at')
                        ->etc();
                });
            });


        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }

    public function testCannotUpdateUserWithInvalidData()
    {
        Carbon::setTestNow(Carbon::now());

        $admin = $this->createAdmin();

        $user = User::factory()->create();

        $this->actingAs($admin)
            ->putJson('/api/users' . '/' . $user->id, [
                'first_name' => Str::random(256),
                'last_name' => Str::random(256),
                'email' => Str::random(256),
                'mobile' => Str::random(256),
                'password' => Str::random(4)
            ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'email',
                'mobile',
                'password'
            ]);
    }

    public function testCannotDeleteUserWithoutPermission()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->deleteJson('/api/users' . '/' . $user->id)
            ->assertForbidden();
    }

    public function testCanDeleteUser()
    {
        Carbon::setTestNow(Carbon::now());

        $admin = $this->createAdmin();

        $user = User::factory()->create();

        $this->actingAs($admin)
            ->deleteJson('/api/users' . '/' . $user->id)
            ->assertNoContent();

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => now()
        ]);
    }

    public function testValidationErrorsAreReturnedForMissingFields()
    {
        Carbon::setTestNow(Carbon::now());

        $admin = $this->createAdmin();

        $user = User::factory()->create();

        $this->actingAs($admin)
            ->postJson('/api/users', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'email',
                'mobile'
            ]);

        $this->actingAs($admin)
            ->putJson('/api/users' . '/' . $user->id, [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'first_name',
                'last_name',
                'email',
                'mobile'
            ]);
    }
}
