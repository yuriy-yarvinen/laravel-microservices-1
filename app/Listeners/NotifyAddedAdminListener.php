<?php

namespace App\Listeners;

use App\Events\AdminAddedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyAddedAdminListener
{

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(AdminAddedEvent $event)
    {
        $user = $event->user;
        Mail::send('admin.adminAdded', [], function(Message $message) use ($user){
            $message->to($user->email);
            $message->substr('new user');
        });
    }
}
