<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserListResource;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('list', User::class);

        $userQuery = User::query()
            ->when($request->input('search'), function (Builder $query, string $value) {
                $query->where('first_name', 'like', "%$value%")
                    ->orWhere('last_name',  "%$value%");
            })
            ->when($request->input('sortBy'), function (Builder $query, string $value) {
                $query->orderBy('first_name', $value);
            });

        return UserListResource::collection($userQuery->paginate(50));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        Gate::authorize('create', User::class);

        $user = new User();

        $user->fill($request->safe()->only([
            'first_name',
            'last_name',
            'email',
            'mobile',
        ]));

        // generate random password when admin create a user 
        // as the user will need to reset the password before they can login
        $user->password = Hash::make(Str::random(10));

        $user->save();

        event(new Registered($user));

        return UserResource::make($user);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        Gate::authorize('view', $user);

        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        Gate::authorize('update', $user);

        $user->fill($request->safe()->only([
            'first_name',
            'last_name',
            'email',
            'mobile',
        ]));

        if ($request->input('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return UserResource::make($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', $user);

        $user->delete();

        return response()->noContent();
    }
}
