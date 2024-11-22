<?php

namespace App\Providers;

use App\Events\BookAddedEvent;
use App\Listeners\LogBookAddedListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BookAddedEvent::class => [
            LogBookAddedListener::class,
        ],
    ];
}
