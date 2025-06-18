<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Company;
use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FileTest extends TestCase
{
    use RefreshDatabase;

    public function testCannotGetFileWithoutPermission()
    {
        $user = User::factory()->create();
        $file = File::factory()->for(Company::factory(), 'owner')->create();

        $this->actingAs($user)
            ->getJson('/api/files/' . $file->id)
            ->assertForbidden();
    }

    public function testCannotDeleteFileWithPermission()
    {
        $user = User::factory()->create();
        $file = File::factory()->for(Company::factory(), 'owner')->create();

        $this->actingAs($user)
            ->getJson('/api/files/' . $file->id)
            ->assertForbidden();
    }

    public function testCanDeleteFileWithPermission()
    {
        $user = User::factory()->create();
        $file = File::factory()->for(Company::factory(), 'owner')->create();

        $user->givePermissionTo(PermissionEnum::WriteCompany);

        $this->actingAs($user)
            ->deleteJson('/api/files/' . $file->id)
            ->assertNoContent();

        $this->assertSoftDeleted('files', [
            'id' => $file->id
        ]);
    }
}
