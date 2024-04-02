<?php

namespace Javaabu\Paperless\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Service extends Model
{
    use LogsActivity;
    use SoftDeletes;

    protected static array $logAttributes = ['*'];
    protected static bool $logOnlyDirty = true;

    protected $fillable = [
        'name',
        'code',
        'fee',
    ];

    protected array $searchable = [
        'name',
        'code',
    ];

    public function getAdminUrlAttribute(): string
    {
        return route('admin.services.edit', $this);
    }

    public function code(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => str($value)->slug('_')->upper()->__toString(),
        );
    }

    public function url(string $action = 'show'): string
    {
        return route("admin.services.$action", $this);
    }

    public function getActivitylogOptions(): LogOptions
    {
        // TODO: Implement getActivitylogOptions() method.
    }
}
