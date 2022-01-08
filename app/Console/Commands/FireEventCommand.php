<?php

namespace App\Console\Commands;

use App\Order;
use App\Jobs\OrderComplited;
use Illuminate\Console\Command;

class FireEventCommand extends Command
{
    protected $signature = 'fire';

    public function handle()
    {
        $order = Order::find(50);

        $orderArray = $order->toArray();
        $orderArray['admin_total'] = $order->admin_total;
        $orderArray['influencer_total'] = $order->influencer_total;

        OrderComplited::dispatch($orderArray);
    }
}
