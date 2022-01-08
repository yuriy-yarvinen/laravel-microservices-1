<?php

namespace App\Listeners;

use App\Services\UserService;
use App\Events\OrderCompletedEvent;
use Illuminate\Support\Facades\Redis;

class UpdateRankingsListener
{
    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->order;

        $revenue = $order->influencer_total;

        $user = (new UserService())->get($order->user_id);

        Redis::zincrby('rankings', $revenue, $user->email);
    }
}
