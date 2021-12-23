<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UserCreateRequest;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        return User::paginate();
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }
    public function user()
    {
        return Auth::user();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $user = User::create(
            $request->only(['first_name', 'last_name', 'email']) +
                [
                    'password' => Hash::make(1234),
                    // 'password' => Hash::make($request->input('password')),
                ]
        );

        return response($user, Response::HTTP_CREATED);
    }

    public function update($id, Request $request)
    {

        $request->validate([
            'email' => 'email',
        ]);

        $user = User::findOrfail($id);
        $user->update($request->only(['first_name', 'last_name', 'email']));


        return response($user, Response::HTTP_ACCEPTED);
    }

    public function updateInfo(Request $request)
    {

        $request->validate([
            'email' => 'email',
        ]);

        $user = Auth::user();
        $user->update($request->only(['first_name', 'last_name', 'email']));


        return response($user, Response::HTTP_ACCEPTED);
    }

    public function updatePassword($id, Request $request)
    {

        $request->validate([
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ]);

        $user = Auth::user();
        $user->update([
           'password' => Hash::make($request->input('password'))
        ]);


        return response($user, Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        User::destroy($id);

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
