<?php

namespace App\Providers;

use App\Listeners\SetUserLocationFromIp;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(Login::class, SetUserLocationFromIp::class);
    }
}
