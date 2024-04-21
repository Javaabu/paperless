<?php

namespace Javaabu\Paperless\Domains\DocumentTypes;

use Illuminate\Database\Eloquent\Model;
use Javaabu\Helpers\AdminModel\AdminModel;
use Javaabu\Activitylog\Traits\LogsActivity;
use Javaabu\Helpers\AdminModel\IsAdminModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentType extends Model implements AdminModel
{
    use IsAdminModel;
    use LogsActivity;
    use SoftDeletes;

    protected static array $logAttributes = ['*'];
    protected static bool $logOnlyDirty   = true;

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
        return match ($action) {
            'index'  => route('admin.document-types.index'),
            'create' => route('admin.document-types.create'),
            default  => route("admin.document-types.$action", $this),
        };
    }
}
