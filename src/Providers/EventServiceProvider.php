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

    protected function configureEmailVerification()
    {
        // fix for Registered Event listener getting registered multiple times
        // see https://github.com/laravel/framework/issues/50783#issuecomment-2072411615
    }

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
