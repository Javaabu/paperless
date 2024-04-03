<?php

namespace Javaabu\Paperless\Models;

use App\Helpers\AdminModel\AdminModel;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\AdminModel\IsAdminModel;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EntityType extends Model implements AdminModel
{
    use IsAdminModel;
    use LogsActivity;

    protected static array $logAttributes = ['*'];

    protected static bool $logOnlyDirty = true;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function entities(): HasMany
    {
        return $this->hasMany(Entity::class);
    }

    public function scopeWithoutIndividual($query): void
    {
        $query->where('slug', '!=', 'individual');
    }

    public function scopeSearch($query, $search): void
    {
        $query->where('name', 'like', '%'.$search.'%');
    }

    public function getAdminUrlAttribute(): string
    {
        return route('admin.entity-types.edit', $this);
    }
}
