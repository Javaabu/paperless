<?php

namespace Javaabu\Paperless\Support\StatusEvents\Traits;

use Javaabu\StatusEvents\Interfaces\Trackable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Javaabu\StatusEvents\Interfaces\TrackingSubject;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Javaabu\Paperless\Support\StatusEvents\Models\StatusEvent;

trait HasStatusEvents
{
    public static function bootHasStatusEvents(): void
    {
        static::deleted(function (Trackable $model) {
            if (! method_exists($model, 'isForceDeleting') || $model->isForceDeleting()) {
                $model->statusEvents()->delete();
            }
        });
    }

    public function statusEvents(): MorphMany
    {
        return $this->morphMany(StatusEvent::class, 'trackable');
    }

    public function latestRemarks(): Attribute
    {
        return Attribute::get(fn () => $this->statusEvents()->orderBy('created_at', 'DESC')->first()?->remarks);
    }

    public function createStatusEvent(string $status, string $remarks = null, ?TrackingSubject $user = null)
    {
        $user ??= auth()->user();

        $status_event = StatusEvent::createFromInput($status, $remarks, $user);

        $this->statusEvents()->save($status_event);

        return $status_event->fresh();
    }
}
