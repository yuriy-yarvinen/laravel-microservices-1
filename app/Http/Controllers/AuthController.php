<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class AuthController
{
    public function login(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'email' => 'required',
            'scope' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();

            $scope = $request->input('scope');

            if ($user->isInfluencer() && $scope !== 'influencer') {
                return response([
                    'error' => 'Access denied!',
                ], Response::HTTP_FORBIDDEN);
            }

            $token = $user->createToken($scope, [$scope])->accessToken;

            $cookie = \cookie('jwt', $token, 3600);

            return response(['token' => $token])->withCookie($cookie);
        }

        return response([
            'error' => 'Invalid credentials'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $cookie = \Cookie::forget('jwt');

        return response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ]);

        $user = User::create(
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'is_influencer' => 1
            ]
        );

        return response($user, Response::HTTP_CREATED);
    }

    public function user()
    {
        $user = Auth::user();

        $userResource = new UserResource($user);

        if($user->isInfluencer()){
            return $userResource;
        }

        return $userResource->additional([
            'data' => [
                'permissions' => $user->permissions()
            ]
        ]);
    }

    public function updateInfo(Request $request)
    {

        $request->validate([
            'email' => 'email',
        ]);

        $user = Auth::user();
        $user->update($request->only(['first_name', 'last_name', 'email']));


        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required',
            'password_confirm' => 'required|same:password',
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
