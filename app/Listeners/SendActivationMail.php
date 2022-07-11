<?php

namespace App\Listeners;

use App\Events\CreateUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendActivationMail implements ShouldQueue
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
     * Handle the event.
     *
     * @param  \App\Events\CreateUser  $event
     * @return void
     */
    public function handle(CreateUser $event)
    {
        // Send email here
    }
}
