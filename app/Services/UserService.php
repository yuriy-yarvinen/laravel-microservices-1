<?php

namespace App\Services;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class UserService{

	public function headers()
	{
		$headers = [];
        if($jwt = request()->cookie('jwt')){
            $headers['Authorization'] = "Bearer {$jwt}";
        }

		if(request()->headers->get('Authorization')){
			$headers['Authorization'] = request()->headers->get('Authorization');
		}

		return $headers;
	}

	public function getUser(): User
	{
        $json = Http::withHeaders($this->headers())->get(config('microservices_urls.USER_SERVICE_URL')."/user")->json();

		$user = new User();
		$user->id = $json['id'];
		$user->last_name = $json['last_name'];
		$user->first_name = $json['first_name'];
		$user->email = $json['email'];
		$user->is_influencer = $json['is_influencer'];
        
		return $user;
	}

	public function isAdmin()
	{
		return Http::withHeaders($this->headers())->get(config('microservices_urls.USER_SERVICE_URL')."/admin")->successful();
	}
	
	public function isInfluencer()
	{
		return Http::withHeaders($this->headers())->get(config('microservices_urls.USER_SERVICE_URL')."/influencer")->successful();
	}

	public function allows($ability, $arguments)
	{
		Gate::forUser($this->getUser())->authorize($ability, $arguments);
	}

	public function all($page)
	{
		return Http::withHeaders($this->headers())->get(config('microservices_urls.USER_SERVICE_URL')."/users?page={$page}")->json();
	}
}