<?php

namespace Javaabu\Paperless\Domains\EntityTypes;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class EntityType extends Model
{
    use LogsActivity;

    protected static array $logAttributes = ['*'];

    protected static bool $logOnlyDirty = true;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function scopeSearch($query, $search): mixed
    {
        return $query->where('name', 'like', '%'.$search.'%');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                         ->logExcept($this->hidden)
                         ->logOnly(static::$logAttributes);
    }
}
