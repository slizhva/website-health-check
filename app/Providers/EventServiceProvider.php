<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\LinkUnavailable;
use App\Events\LinkCheckException;
use App\Events\LinkCommandSuccess;
use App\Listeners\RunLinkCommand;
use App\Listeners\LinkStatusNotification;
use App\Listeners\CommandNotification;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        LinkUnavailable::class => [
            LinkStatusNotification::class,
            RunLinkCommand::class,
        ],
        LinkCheckException::class => [
            LinkStatusNotification::class,
        ],
        LinkCommandSuccess::class => [
            CommandNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
