<?php

namespace Javaabu\Paperless\Support\StatusEvents\Models;

use Javaabu\StatusEvents\Interfaces\TrackingSubject;

class StatusEvent extends \Javaabu\StatusEvents\Models\StatusEvent
{
    public static function createFromInput(string $status, ?string $remarks = null, ?TrackingSubject $user = null): \Javaabu\StatusEvents\Models\StatusEvent
    {
        $status_event = new StatusEvent();
        $status_event->status = $status;
        $status_event->remarks = $remarks;
        $status_event->event_at = now();

        if ($user) { // system events will not have user
            $status_event->user()->associate($user);
        }

        return $status_event;
    }

    public function getStatusClass()
    {
        return $this->trackable->status;
        // $trackable_class = Model::getActualClassNameForMorph($this->trackable_type);
        // return $trackable_class::$status_class;
    }

}
