<?php

namespace App\Http\Middleware;

use Closure;
use App\Services\UserService;
use Illuminate\Auth\AuthenticationException;

class InfluencerScope
{

    public function handle($request, Closure $next)
    {
        if((new UserService())->isInfluencer()){

            return $next($request);
        }

        throw new AuthenticationException;
    }
}
