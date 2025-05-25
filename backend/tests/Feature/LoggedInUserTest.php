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

class LoggedInUserTest extends TestCase
{
    use RefreshDatabase;
    public function testUnauthenticatedUserCannotViewLoggedInUser()
    {
        User::factory()->create();

        $this->getJson('/api/user')
            ->assertUnauthorized();
    }

    public function testCanGetLoggedInUserInfo()
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->getJson('/api/user/')
            ->assertSuccessful()
            ->assertJson(function (AssertableJson $json) use ($admin) {
                $json->has('data', function (AssertableJson $json) use ($admin) {
                    $json
                        ->where('name', $admin->name)
                        ->where('email', $admin->email)
                        ->where('status', $admin->status->title())
                        ->where('roles', $admin->getRoleNames())
                        ->where('abilities', $admin->abilities)
                        ->etc();
                });
            });
    }
}
