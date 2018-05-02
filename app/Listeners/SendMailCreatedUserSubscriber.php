<?php

namespace App\Listeners;

use App\Mail\UserMail;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendMailCreatedUserSubscriber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Premier paramétre $event l'action => exemple (lecture GET sur le model Users)
     * Deuxième paramétre $event la méthode qui est appeler !
     *
     * Register the listeners for the subscriber.
     * @param $events
     */
    public function subscribe($events)
    {
        $events->listen('eloquent.created: App\User',
            [$this, 'onSendMailForUsers']
        );
    }

    /**
     * Method call is send mail for user in created
     */
    public function onSendMailForUsers()
    {
        /**
         * Send Mail For new User
         */
        $last_create_user = User::orderBy('id', 'desc')->first();
        Mail::to($last_create_user->email)->send(new UserMail);
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
    }
}
