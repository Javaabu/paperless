<?php

namespace Javaabu\Paperless\Support\Media;

use Illuminate\Support\Str;
use Javaabu\Helpers\Media\AllowedMimeTypes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    /**
     * A search scope
     * @param $query
     * @param $search
     * @return mixed
     */
    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%'.$search.'%');
    }


    /**
     * With relations scope
     * @param $query
     * @return mixed
     */
    public function scopeWithRelations($query)
    {
        return $query->with('model');
    }

    /**
     * Get url attribute
     */
    public function getUrlAttribute(): string
    {
        return $this->getUrl();
    }

    /**
     * Check if is an image attachment
     */
    public function isImageAttachment(): bool
    {
        return Str::startsWith($this->collection_name, 'images:');
    }

    /**
     * Get delete url attribute
     */
    public function getDeleteUrlAttribute(): string
    {
        $controller = $this->isImageAttachment() ? 'Images' : 'Documents';
        $controller = Str::kebab($controller);

        return route('api.' . $controller . '.destroy', $this);
    }

    /**
     * Get type attribute
     */
    public function getTypeSlugAttribute(): string
    {
        return AllowedMimeTypes::getType($this->mime_type);
    }

    /**
     * Get description attribute
     */
    public function getDescriptionAttribute(): string
    {
        return $this->getCustomProperty('description');
    }

    /**
     * Get icon attribute
     */
    public function getIconAttribute(): string
    {
        return $this->getIcon();
    }

    /**
     * Get icon attribute
     */
    public function getWebIconAttribute(): string
    {
        return $this->getWebIcon();
    }

    public function getWebIcon(string $prefix = 'fa fa-'): string
    {
        $icon = AllowedMimeTypes::getWebIcon($this->mime_type);

        return $prefix.($icon ?: 'file');
    }

    public function getIcon(string $prefix = 'zmdi zmdi-'): string
    {
        $icon = AllowedMimeTypes::getIcon($this->mime_type);

        return $prefix.($icon ?: 'file');
    }

    /**
     * Type scope
     *
     * @param $query
     * @param string $type
     * @return mixed
     */
    public function scopeHasType($query, string $type)
    {
        return $query->whereIn('mime_type', AllowedMimeTypes::getAllowedMimeTypes($type));
    }

    /**
     * Belongs to a document type
     *
     * @return BelongsTo
     */
    public function documentType(): BelongsTo
    {
        return $this->belongsTo(config('paperless.models.document_type'));
    }
}
