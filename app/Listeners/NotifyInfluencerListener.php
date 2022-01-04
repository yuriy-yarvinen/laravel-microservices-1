<?php

namespace App\Listeners;

use Illuminate\Mail\Message;
use App\Events\OrderCompletedEvent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyInfluencerListener
{

    public function handle(OrderCompletedEvent $event)
    {
        $order = $event->order;
        
        Mail::send('influencer.influencer', ['order'=>$order], function(Message $message) use ($order){
            $message->to($order->influencer_email);
            $message->subject('new order');
        });
    }
}
