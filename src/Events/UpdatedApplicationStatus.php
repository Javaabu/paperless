<?php

namespace Javaabu\Paperless\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Javaabu\Paperless\Domains\Applications\Application;
use Javaabu\Paperless\Support\StatusEvents\Models\StatusEvent;

class UpdatedApplicationStatus
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Application $application,
        public StatusEvent $statusEvent,
    ) {
    }
}
