<?php


namespace App\Subscribers\Models;


use App\Events\Models\User\UserCreated;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Events\Dispatcher;

class UserSubscriber
{

    public function subscribe(Dispatcher $events)
    {
        $events->listen(UserCreated::class, SendWelcomeEmail::class);
    }
}