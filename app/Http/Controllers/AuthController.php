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
            return $userResource;
        }

        return $userResource->additional([
            'data' => [
                'permissions' => $user->permissions()
            ]
        ]);
    }

}
