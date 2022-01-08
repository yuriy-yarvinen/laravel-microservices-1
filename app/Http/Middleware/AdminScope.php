<?php

namespace App\Http\Middleware;

use App\Services\UserService;
use Closure;
use Illuminate\Auth\AuthenticationException;

class AdminScope
{
    
    public function handle($request, Closure $next)
    {
        if((new UserService())->isAdmin()){

            return $next($request);
        }

        throw new AuthenticationException;

    }
}
