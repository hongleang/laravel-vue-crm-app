<?php

namespace Tests\Unit;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class FileTest extends TestCase
{
    use RefreshDatabase;

    public function testCanGetRelativePath()
    {
        $file = File::factory()
            ->for($this->createUser(), 'owner')
            ->create([
                'user_id' => $this->createUser()->id
            ]);

        $this->assertTrue(Str::startsWith($file->relative_path, $file->directory));
        $this->assertTrue(Str::endsWith($file->relative_path, $file->hash));
    }

    public function testCanGetBase64()
    {
        $file = File::factory()->contents('test')->for(User::factory(), 'owner')->create([
            'user_id' => $this->createUser()->id
        ]);
        $this->assertEquals(base64_encode('test'), $file->base64);
    }

    public function testCanGetFileContent()
    {
        $file = File::factory()->contents('test')->for(User::factory(), 'owner')->create([
            'user_id' => $this->createUser()->id
        ]);
        $this->assertEquals('test', $file->contents);
    }
}
