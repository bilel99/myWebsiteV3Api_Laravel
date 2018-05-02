<?php

namespace App\Providers;

use App\Listeners\SendMailCreatedUserSubscriber;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
    ];

    /**
     * The Event Subscriber mappings for the application.
     * PS : Dans mon cas j'ai choisie d'allez chercher la classe directement
     */
    protected $subscribe = [
        SendMailCreatedUserSubscriber::class
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
