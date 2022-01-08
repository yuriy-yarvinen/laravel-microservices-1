<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\UserService;

class AuthController
{

    public function user()
    {
      
        $user = (new UserService())->getUser();
        
        $userResource = new UserResource($user);

        if($user->isInfluencer()){
            return $userResource->additional([
                'data' => [
                    'revenue' => $user->revenue
                ]
            ]);
        }

        return $userResource->additional([
            'data' => [
                'role' => $user->role(),
                'permissions' => $user->permissions(),
            ]
        ]);
    }

}
