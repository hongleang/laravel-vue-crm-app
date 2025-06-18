<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    public function show(File $file): StreamedResponse
    {
        Gate::authorize('view', $file);

        return $file->download();
    }

    public function destroy(File $file): Response
    {
        Gate::authorize('delete', $file);
        
        $file->delete();

        return response()->noContent();
    }
}
