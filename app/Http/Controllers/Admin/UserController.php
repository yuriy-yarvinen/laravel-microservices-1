<?php

namespace App\Http\Controllers\Admin;

use App\UserRole;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Jobs\AdminAdded;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    public function index(Request $request)
    {
        (new UserService())->allows('view', 'users');

        return (new UserService())->all($request->input('page', 1));
    }

    public function show($id)
    {
        (new UserService())->allows('view', 'users');

        $user = (new UserService())->get($id);

        $userResource = new UserResource($user);

        $userResource->additional([
            'data' => [
                'role' => $user->role()
            ]
        ]);

        return $userResource;

    }

    public function store(Request $request)
    {
        (new UserService())->allows('edit', 'users');

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'role_id' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $data = $request->only(['first_name', 'last_name', 'email']) + ['password' => 1234];

        $user = (new UserService())->create($data);

        UserRole::create([
            'user_id' => $user->id,
            'role_id' => $request->input('role_id'),
        ]);

        AdminAdded::dispatch($user->email);

        return response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function update($id, Request $request)
    {
        (new UserService())->allows('edit', 'users');

        $request->validate([
            'email' => 'email',
        ]);

        $data = $request->only(['first_name', 'last_name', 'email']);

        $user = (new UserService())->update($id, $data);

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

        (new UserService())->allows('edit', 'users');

        (new UserService())->delete($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
