<?php

namespace App\Listeners;

use Illuminate\Mail\Message;
use App\Events\OrderCompletedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAdminListener
{

    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->order;
        Mail::send('influencer.admin', ['order'=>$order], function(Message $message){
            $message->to('admin@admin.com');
            $message->substr('new order');
        });

    }
}
