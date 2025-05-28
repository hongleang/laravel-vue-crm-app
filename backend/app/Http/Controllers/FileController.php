<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function show(File $file): StreamedResponse
    {
        return $file->download();
    }

    public function destroy(File $file): Response
    {
        $file->delete();

        return response()->noContent();
    }
}
