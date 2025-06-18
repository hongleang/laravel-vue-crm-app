<?php

namespace Tests\Feature;

use App\Enums\PermissionEnum;
use App\Models\Company;
use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Illuminate\Support\Str;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function testUnauthenticatedUserCannotAccessCompanyRoutes()
    {
        User::factory()->create();

        $this->getJson('/api/companies')
            ->assertUnauthorized();
    }

    public function testCannotListCompaniesWithoutPermission()
    {
        $user = User::factory()->active()->create();

        $this->actingAs($user)
            ->getJson('/api/companies')
            ->assertForbidden();
    }

    public function testCanListCompaniesWithPaginatedData()
    {
        $admin = $this->createAdmin();

        $company = Company::factory()->create();

        $this->actingAs($admin)
            ->getJson('/api/companies')
            ->assertSuccessful()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->hasAll(['data', 'meta', 'links'])
                    ->has('data', 1, fn(AssertableJson $json) => $json
                        ->where('name', $company->name)
                        ->where('industry', $company->industry)
                        ->where('email', $company->email)
                        ->where('address', $company->address)
                        ->etc())
            );
    }

    public function testSearchCompanyByNameReturnsMatchingResults()
    {
        $admin = $this->createAdmin();

        [$companyA, $companyB, $companyC] = Company::factory(3)
            ->sequence(
                ['name' => 'AAA'],
                ['name' => 'CCC'],
                ['name' => 'BAA']
            )
            ->create();

        $this->actingAs($admin)
            ->getJson('/api/companies?' . http_build_query([
                'search' => 'aa'
            ]))
            ->assertSuccessful()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->hasAll(['data', 'meta', 'links'])
                    ->has('data', 2)
                    ->where('data.0.id', $companyA->id)
                    ->where('data.1.id', $companyC->id)
            );
    }
    public function testSortNameByOrder()
    {
        $admin = $this->createAdmin();

        [$companyA, $companyB, $companyC] = Company::factory(3)
            ->sequence(
                ['name' => 'AAA'],
                ['name' => 'CCC'],
                ['name' => 'BAA']
            )
            ->create();

        $this->actingAs($admin)
            ->getJson('/api/companies?' . http_build_query([
                'sortBy' => 'desc'
            ]))
            ->assertSuccessful()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->hasAll(['data', 'meta', 'links'])
                    ->has('data', 3)
                    ->where('data.0.id', $companyB->id)
                    ->where('data.1.id', $companyC->id)
                    ->where('data.2.id', $companyA->id)
            );

        $this->actingAs($admin)
            ->getJson('/api/companies?' . http_build_query([
                'sortBy' => 'asc'
            ]))
            ->assertSuccessful()
            ->assertJson(
                fn(AssertableJson $json) => $json
                    ->hasAll(['data', 'meta', 'links'])
                    ->has('data', 3)
                    ->where('data.0.id', $companyA->id)
                    ->where('data.1.id', $companyC->id)
                    ->where('data.2.id', $companyB->id)
            );
    }
    public function testCannotViewSingleCompanyWithoutPermission()
    {
        $company = Company::factory()->create();

        $this->actingAs($this->createUser())
            ->getJson("/api/companies/$company->id")
            ->assertForbidden();
    }

    public function testCanViewSingleCompany()
    {
        $admin = $this->createAdmin();

        $company = Company::factory()->create();

        $this->actingAs($admin)
            ->getJson("/api/companies/$company->id")
            ->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data', fn(AssertableJson $json) => $json
                    ->where('name', $company->name)
                    ->where('industry', $company->industry)
                    ->where('email', $company->email)
                    ->where('address', $company->address)
                    ->etc()));
    }
    public function testCannotCreateCompanyWithoutPermission()
    {
        $this->actingAs($this->createUser())
            ->postJson("/api/companies", [
                'name' => 'test company',
                'industry' => 'test industry',
                'email' => 'test email',
                'address' => 'fake address'
            ])
            ->assertForbidden();
    }

    public function testRequiredFieldsForCreateCompany()
    {
        $this->actingAs($this->createAdmin())
            ->postJson("/api/companies", [])
            ->assertUnprocessable()
            ->assertInvalid([
                'name',
                'industry',
                'email',
                'phone',
                'address',
            ]);
    }

    public function testCannotCreateCompanyWithInvalidData()
    {
        $this->actingAs($this->createAdmin())
            ->postJson("/api/companies", [
                'name' => Str::random(256),
                'industry' => Str::random(256),
                'email' => 'test email',
                'address' => Str::random(256)
            ])
            ->assertUnprocessable()
            ->assertInvalid([
                'name',
                'industry',
                'email',
                'address',
            ]);
    }
    public function testCanCreateCompanyWithPermission()
    {
        $user = $this->createUser();
        $user->givePermissionTo(PermissionEnum::WriteCompany);

        $this->actingAs($user)
            ->postJson("/api/companies", [
                'name' => 'test company',
                'industry' => 'test industry',
                'email' => 'test@email.com',
                'phone' => '0445990990',
                'address' => 'fake address'
            ])
            ->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data', fn(AssertableJson $json) => $json
                    ->where('name', 'test company')
                    ->where('industry', 'test industry')
                    ->where('email', 'test@email.com')
                    ->where('address', 'fake address')
                    ->etc()));

        $this->assertDatabaseHas('companies', [
            'name' => 'test company',
            'industry' => 'test industry',
            'email' => 'test@email.com',
            'address' => 'fake address'
        ]);
    }
    public function testCannotUpdateUserWithoutPermission()
    {
        $company = Company::factory()->create();

        $this->actingAs($this->createUser())
            ->putJson("/api/companies/$company->id", [
                'name' => 'test company',
                'industry' => 'test industry',
                'email' => 'test email',
                'address' => 'fake address'
            ])
            ->assertForbidden();
    }
    public function testRequiredFieldsForUpdateCompany()
    {
        $company = Company::factory()->create();

        $this->actingAs($this->createAdmin())
            ->putJson("/api/companies/$company->id", [])
            ->assertUnprocessable()
            ->assertInvalid([
                'name',
                'industry',
                'email',
                'phone',
                'address',
            ]);
    }

    public function testAdminCanUpdateCompanyWithPermission()
    {
        $company = Company::factory()->create();

        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->putJson("/api/companies/$company->id", [
                'name' => 'test company',
                'industry' => 'test industry',
                'email' => 'test@email.com',
                'phone' => '0445990990',
                'address' => 'fake address'
            ])
            ->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data', fn(AssertableJson $json) => $json
                    ->where('id', $company->id)
                    ->where('name', 'test company')
                    ->where('industry', 'test industry')
                    ->where('email', 'test@email.com')
                    ->where('address', 'fake address')
                    ->etc()));

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'test company',
            'industry' => 'test industry',
            'email' => 'test@email.com',
            'address' => 'fake address'
        ]);
    }

    public function testCannotDeleteCompanyWithoutPermission()
    {
        $company = Company::factory()->create();

        $this->actingAs($this->createUser())
            ->deleteJson("/api/companies/$company->id")
            ->assertForbidden();
    }

    public function testAdminCanDeleteCompanyWithPermission()
    {
        $company = Company::factory()->create();

        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->deleteJson("/api/companies/$company->id")
            ->assertNoContent();

        $this->assertSoftDeleted('companies', [
            'id' => $company->id
        ]);
    }

    // File Upload & Deletion
    public function testCanUploadFileToCompany()
    {
        $company = Company::factory()->create();

        $admin = $this->createAdmin();

        Storage::fake('local');

        $file1 = UploadedFile::fake()->create('file1.pdf', 100, 'application/pdf');
        $file2 = UploadedFile::fake()->create('file2.jpg', 200, 'image/png');
        $files = [$file1, $file2];

        $this->actingAs($admin)
            ->postJson("/api/companies/$company->id/upload", [
                'files' => $files
            ])
            ->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data', fn(AssertableJson $json) => $json
                    ->has('files', 2)
                    ->where('files.0.name', 'file1.pdf')
                    ->where('files.0.extension', 'pdf')
                    ->where('files.0.bytes', $file1->getSize())
                    ->has('files.0.hash')
                    ->etc())
                ->etc());

        foreach ($company->files as $file) {
            $this->assertDatabaseHas('files', [
                'id' => $file->id,
                'owner_id' => $company->id,
                'owner_type' => Company::class
            ]);
        }
    }

    public function testCanAddNoteToCompany()
    {
        $company = Company::factory()->create();

        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->postJson("/api/companies/$company->id/note", [
                'content' => 'test note'
            ])
            ->assertSuccessful()
            ->assertJson(fn(AssertableJson $json) => $json
                ->has('data', fn(AssertableJson $json) => $json
                    ->has('notes', 1, fn(AssertableJson $json) => $json
                        ->where('content', 'test note')
                        ->where('created_by', $admin->name)
                        ->etc())
                    ->etc()));

        $this->assertDatabaseHas('notes', [
            'content' => 'test note'
        ]);
    }
}
