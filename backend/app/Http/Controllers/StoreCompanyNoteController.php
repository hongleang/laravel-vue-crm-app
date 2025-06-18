<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyNoteRequest;
use App\Http\Requests\StoreUploadFileRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Models\Note;

class StoreCompanyNoteController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreCompanyNoteRequest $request, Company $company): CompanyResource
    {
        $note = [
            'content' => $request->validated('content'),
            'user_id' => $request->user()->id
        ];

        $company->notes()->create($note);

        return CompanyResource::make($company->load(['files', 'notes']));
    }
}
