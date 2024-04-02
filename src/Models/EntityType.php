<?php

namespace Javaabu\Paperless\Models;

use App\Helpers\AdminModel\AdminModel;
use App\Helpers\AdminModel\IsAdminModel;
use Javaabu\Paperless\Models\Entity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;

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
