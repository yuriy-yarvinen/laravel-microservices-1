<?php

namespace App\Console\Commands;

use App\Order;
use App\Services\UserService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class UpdateRankingsCommand extends Command
{
    protected $signature = 'update:rankings';

    public function handle()
    {

        $users = (new UserService())->getCustomUsers(['is_influencer' => 1]);

        $users->each(function ($user) {
            $orders = Order::where('user_id', $user->id)->where('complete', 1)->get();
            $revenue = $orders->sum(function (Order $order) {
                return (int) $order->influencer_total;
            });

            Redis::zadd('rankings', $revenue, $user->email);
        });
    }
}
