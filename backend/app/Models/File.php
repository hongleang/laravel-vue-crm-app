<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $disk = 'local';
    
    protected $fillable = [
        'name',
        'hash',
        'directory',
        'extension',
        'bytes'
    ];

    public function owner(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Relative path attribute getter.
     */
    public function relativePath(): Attribute
    {
        return new Attribute(
            get: fn() => sprintf('%s/%s', $this->directory, $this->hash)
        );
    }

    /**
     * Storage attribute getter.
     */
    public function storage(): Attribute
    {
        return new Attribute(
            get: fn() => Storage::disk($this->disk)
        );
    }

    /**
     * Contents attribute getter.
     */
    public function contents(): Attribute
    {
        return new Attribute(
            get: fn() => $this->storage->get($this->relativePath)
        );
    }

    /**
     * Size attribute getter.
     */
    public function base64(): Attribute
    {
        return new Attribute(
            get: fn() => base64_encode($this->contents)
        );
    }

    /**
     * Download the file.
     */
    public function download(): StreamedResponse
    {
        return Response::streamDownload(function () {
            echo $this->contents;
        }, $this->name);
    }
}
