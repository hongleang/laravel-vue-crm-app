<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUploadFileRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CompanyUploadFileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreUploadFileRequest $request, Company $company): CompanyResource
    {
        if ($request->has('files')) {
            foreach ($request->validated('files') as $uploadFile) {
                $uploadFile->store('upload');

                $company->files()->create([
                    'directory' => 'upload',
                    'hash' => $uploadFile->hashName(),
                    'name' => $uploadFile->getClientOriginalName(),
                    'extension' => $uploadFile->getClientOriginalExtension(),
                    'bytes' => $uploadFile->getSize()
                ]);
            }
        }

        return CompanyResource::make($company->load('files'));
    }
}
