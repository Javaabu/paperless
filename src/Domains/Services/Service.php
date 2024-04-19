<?php

namespace Javaabu\Paperless\Domains\Services;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Helpers\AdminModel\AdminModel;
use Spatie\Activitylog\Traits\LogsActivity;
use Javaabu\Helpers\AdminModel\IsAdminModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Service extends Model implements AdminModel
{
    use IsAdminModel;
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
        return LogOptions::defaults()
            ->logExcept($this->hidden)
            ->logOnlyDirty();
    }
}
