<?php

namespace Javaabu\Paperless\Models;

use Spatie\MediaLibrary\HasMedia;
use Javaabu\Helpers\Media\UpdateMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Javaabu\Paperless\Domains\Applications\Application;

class FormInput extends Model implements HasMedia
{
    use InteractsWithMedia;
    use UpdateMedia;

    protected $fillable = [
        'value',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function formField(): BelongsTo
    {
        return $this->belongsTo(FormField::class);
    }

    public function fieldGroup(): BelongsTo
    {
        return $this->belongsTo(FieldGroup::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('attachment')
             ->singleFile();
    }

    public function isFilled(): bool
    {
        return $this->formField->getBuilder()->isFilled($this, $this->application, $this->formField);
    }

    public function getValue()
    {
        return $this->formField->getBuilder()->getFormInputValue($this, $this->application, $this->formField);
    }

    public function getAttachmentUrl(string $collection_name, ?int $instance = null): ?string
    {
        $media_filters = [];

        // if (filled($instance)) {
        //     $media_filters = ['instance' => $instance];
        // }

        $media = $this->getFirstMedia($collection_name, $media_filters);

        return $media?->getUrl();
    }
}
