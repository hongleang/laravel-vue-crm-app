<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyListResource;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): ResourceCollection
    {
        Gate::authorize('list', Company::class);

        $companiesQuery = Company::query()
            ->when($request->input('search'), function (Builder $query, string $value) {
                $query->where('name', 'like', "%$value%");
            })
            ->when($request->input('sortBy'), function (Builder $query, string $value) {
                $query->orderBy('name', $value);
            });

        return CompanyListResource::collection($companiesQuery->paginate(20));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request): CompanyResource
    {
        $company = new Company();
        $company->fill($request->safe()->only([
            'name',
            'industry',
            'email',
            'phone',
            'address'
        ]));
        $company->creator_id = $request->user()->id;

        $company->save();

        return CompanyResource::make($company->load(['files', 'notes']));
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company): CompanyResource
    {
        Gate::authorize('view', $company);

        return CompanyResource::make($company->load(['files', 'notes']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company): CompanyResource
    {
        $company->update($request->safe()->only([
            'name',
            'industry',
            'email',
            'phone',
            'address'
        ]));

        return CompanyResource::make($company->load(['files', 'notes']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company): Response
    {
        Gate::authorize('delete', $company);

        $company->delete();

        return response()->noContent();
    }
}
