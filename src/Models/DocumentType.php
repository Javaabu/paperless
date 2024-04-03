<?php

namespace Javaabu\Paperless\Models;

use App\Helpers\AdminModel\HasUrl;
use App\Helpers\AdminModel\AdminModel;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\AdminModel\IsAdminModel;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentType extends Model implements AdminModel, HasUrl
{
    use IsAdminModel;
    use LogsActivity;
    use SoftDeletes;

    protected static array $logAttributes = ['*'];
    protected static bool $logOnlyDirty = true;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    protected array $searchable = [
        'name',
        'description',
    ];

    public function getAdminUrlAttribute(): string
    {
        return route('admin.document-types.edit', $this);
    }

    public function url(string $action = 'show'): string
    {
        return match($action) {
            'index' => route('admin.document-types.index'),
            'create' => route('admin.document-types.create'),
            default => route("admin.document-types.$action", $this),
        };
    }
}
