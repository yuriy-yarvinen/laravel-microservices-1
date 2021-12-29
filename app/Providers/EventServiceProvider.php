<?php

namespace App\Providers;

use App\Events\AdminAddedEvent;
use App\Events\OrderCompletedEvent;
use Illuminate\Support\Facades\Event;
use App\Listeners\NotifyAdminListener;
use Illuminate\Auth\Events\Registered;
use App\Listeners\NotifyAddedAdminListener;
use App\Listeners\NotifyInfluencerListener;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
            
        ],
        OrderCompletedEvent::class => [
            NotifyAdminListener::class,
            NotifyInfluencerListener::class,
        ],
        AdminAddedEvent::class => [
            NotifyAddedAdminListener::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
