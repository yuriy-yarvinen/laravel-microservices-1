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

	public function request()
	{
		return Http::withHeaders($this->headers());
	}

	public function getUser(): User
	{
        $json = $this->request()->get(config('microservices_urls.USER_SERVICE_URL')."/user")->json();

		return new User($json);
	}

	public function isAdmin()
	{
		return $this->request()->get(config('microservices_urls.USER_SERVICE_URL')."/admin")->successful();
	}
	
	public function isInfluencer()
	{
		return $this->request()->get(config('microservices_urls.USER_SERVICE_URL')."/influencer")->successful();
	}

	public function allows($ability, $arguments)
	{
		Gate::forUser($this->getUser())->authorize($ability, $arguments);
	}

	public function all($page)
	{
		return $this->request()->get(config('microservices_urls.USER_SERVICE_URL')."/users?page={$page}")->json();
	}

	public function getCustomUsers($data)
	{
		return $this->request()->post(config('microservices_urls.USER_SERVICE_URL')."/customUsers", $data)->json();
	}

	public function get($id): User
	{
		$json = $this->request()->get(config('microservices_urls.USER_SERVICE_URL')."/users/{$id}")->json();

		return new User($json);

	}

	public function create($data): User
	{
		$json = $this->request()->post(config('microservices_urls.USER_SERVICE_URL')."/users", $data)->json();

		return new User($json);

	}

	public function update($id, $data): User
	{
		$json = $this->request()->put(config('microservices_urls.USER_SERVICE_URL')."/users/{$id}", $data)->json();

		return new User($json);

	}

	public function delete($id)
	{
		return $this->request()->delete(config('microservices_urls.USER_SERVICE_URL')."/users/{$id}")->successful();
	}
}