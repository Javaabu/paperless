<?php

namespace Javaabu\Paperless\Providers;

use Javaabu\Paperless\Events\UpdatedApplicationStatus;
use Javaabu\Paperless\Events\UpdatingApplicationStatus;

class EventServiceProvider extends \Illuminate\Foundation\Support\Providers\EventServiceProvider
{
    protected $listen = [
        UpdatingApplicationStatus::class => [
            // ...
        ],
        UpdatedApplicationStatus::class  => [
            \Javaabu\Paperless\Listeners\SendApplicationStatusUpdateNotification::class,
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
    }
}
