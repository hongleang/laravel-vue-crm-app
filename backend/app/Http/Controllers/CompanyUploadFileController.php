<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUploadFileRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyUploadFileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreUploadFileRequest $request, Company $company): CompanyResource
    {
        if($request->has('files')) {
        }

        return CompanyResource::make($company->load('files'));
    }
}
