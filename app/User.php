<?php

namespace App;

use App\Role;
use App\UserRole;

class User
{

    public $id;
    public $first_name;
    public $last_nam;
    public $email;
    public $is_influencer;

    public function role()
    {
        $userRole = UserRole::where('user_id', $this->id)->first();

        return Role::find($userRole->role_id);
    }

    public function permissions()
    {
        return $this->role()->permissions->pluck('name');
    }

    public function hasAccess($access)
    {
        return $this->permissions()->contains($access);
    }

    public function isAdmin()
    {
        return $this->is_influencer === 0;
    }

    public function isInfluencer()
    {
        return $this->is_influencer === 1;
    }

    public function getRevenueAttribute()
    {
        $orders = Order::where('user_id', $this->id)->where('complete', 1)->get();

        return $orders->sum(function (Order $order) {
            return $order->influencer_total;
        });
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

}
