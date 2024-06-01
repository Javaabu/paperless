<?php

namespace Javaabu\Paperless\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Javaabu\Paperless\Domains\Applications\Application;

class UpdatedApplicationStatus
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Application $application,
    ) {}
}
