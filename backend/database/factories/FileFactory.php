<?php

namespace Database\Factories;

use App\Models\File;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'directory' => 'uploads',
            'extension' => fake()->randomElement(['csv', 'doc', 'xls', 'pdf', 'ppt', 'jpg', 'png', 'mp3', 'mp4', 'zip', '7z']),
            'name' => fn ($attributes) => sprintf('%s.%s', Str::snake(fake()->words(rand(1, 4), true)), $attributes['extension']),
            'hash' => fn ($attributes) => sprintf('%s.%s', fake()->uuid(), $attributes['extension']),
            'bytes' => 0,
        ];
    }

    public function contents(string $contents)
    {
        return $this->afterCreating(function (File $file) use ($contents) {
            $physical = UploadedFile::fake()->createWithContent($file->name, $contents);

            $physical->store($file->directory);

            $file->update(['hash' => $physical->hashName(), 'bytes' => $physical->getSize()]);
        });
    }

    public function ownedBy(Factory | Model $owner): self
    {
        return $this->for($owner, 'owner');
    }
}
