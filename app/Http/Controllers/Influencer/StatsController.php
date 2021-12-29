<?php

namespace App\Http\Controllers\Influencer;

use App\Link;
use App\Order;
use App\User;
use Illuminate\Http\Request;

class StatsController
{
    public function index(Request $request)
    {
        $user = $request->user();

        $links = Link::where('user_id', $user->id)->get();

        return $links->map(function (Link $link) {
            $orders = Order::where('code', $link->code)->where('complete', 1)->get();

            return [
                'code' => $link->code,
                'count' => $orders->count(),
                'revenue' => $orders->sum(function (Order $order) {
                    return $order->influencer_total;
                }),
            ];
        });
    }

    public function rankings()
    {
        $users = User::where('is_influencer', 1)->get();

        $rankings = $users->map(function (User $user) {
            $orders = Order::where('user_id', $user->id)->where('complete', 1)->get();

            return [
                'name' => $user->full_name,
                'revenue' => $orders->sum(function (Order $order) {
                    return (int) $order->influencer_total;
                }),
            ];
        });

        return $rankings->sortByDesc('revenue')->values();
    }
}