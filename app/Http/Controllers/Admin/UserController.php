<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\UserRole;
use Illuminate\Http\Request;
use App\Events\AdminAddedEvent;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserCreateRequest;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function index()
    {
        Gate::authorize('view', 'users');

        $users = User::paginate(); 

        return UserResource::collection($users);
    }

    public function show($id)
    {
        Gate::authorize('view', 'users');

        $user = User::findOrFail($id);

        return new UserResource($user);
    }

    public function store(Request $request)
    {
        Gate::authorize('edit', 'users');

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'role_id' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $user = User::create(
            $request->only(['first_name', 'last_name', 'email']) +
                [
                    'password' => Hash::make(1234),
                    // 'password' => Hash::make($request->input('password')),
                ]
        );

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id'),
        ]);

        event(new AdminAddedEvent($user));

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function update($id, Request $request)
    {
        Gate::authorize('edit', 'users');

        $request->validate([
            'email' => 'email',
        ]);

        $user = User::findOrfail($id);
        $user->update($request->only(['first_name', 'last_name', 'email']));

        UserRole::where([
            'user_id' => $user->id,
        ])->delete();

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id'),
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }


    public function destroy($id)
    {

        Gate::authorize('edit', 'users');

        User::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
