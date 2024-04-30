<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Collection;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Models\FormField;
use Javaabu\Paperless\Models\FieldGroup;
use Javaabu\Paperless\Models\FormSection;
use Javaabu\Helpers\AdminModel\AdminModel;
use Javaabu\Helpers\Media\AllowedMimeTypes;
use Javaabu\Paperless\Interfaces\Applicant;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\InteractsWithMedia;
use Javaabu\Helpers\AdminModel\IsAdminModel;
use Spatie\MediaLibrary\MediaCollections\File;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Javaabu\Paperless\Domains\EntityTypes\EntityType;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Javaabu\Paperless\Domains\Applications\Application;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Javaabu\Paperless\Domains\ApplicationTypes\Traits\HasApplicationSpecificPermissions;
use Javaabu\Paperless\Domains\ApplicationTypes\Categories\Casts\ApplicationTypeCategoryAttribute;

class ApplicationType extends Model implements AdminModel, HasMedia
{
    use HasApplicationSpecificPermissions;
    use InteractsWithMedia;
    use IsAdminModel;
    use LogsActivity;

    protected static array $logAttributes = ['*'];
    protected static bool $logOnlyDirty = true;

    protected $fillable = [
        'description',
        'eta_duration',
        'alert_duration',
    ];

    protected array $searchable = [
        'name',
        'code',
        'description',
    ];

    public function casts(): array
    {
        return [
            'application_category'       => ApplicationTypeCategoryAttribute::class,
            'description'                => 'array',
            'allow_additional_documents' => 'bool',
        ];
    }

    public function applications(): HasMany
    {
        $application_model = config('paperless.models.application');

        return $this->hasMany($application_model);
    }

    public function documentTypes(): BelongsToMany
    {
        $document_type_model = config('paperless.models.document_type');

        return $this->belongsToMany($document_type_model, 'document_type_application_type')
                    ->withPivot(['id', 'is_required']);
    }

    public function services(): BelongsToMany
    {
        $service_model = config('paperless.models.service');

        return $this->belongsToMany($service_model, 'application_type_service')
                    ->withPivot(['id', 'is_applied_automatically']);
    }

    public function entityTypes(): BelongsToMany
    {
        return $this->belongsToMany(EntityType::class, 'entity_type_application_type');
    }

    public function assignedUsers(): BelongsToMany
    {
        $user_model = config('paperless.models.user');

        return $this->belongsToMany(
            $user_model,
            'application_type_users',
            'application_type_id',
            'assigned_user_id',
            'id',
            'id'
        )
                    ->withPivot(['id', 'is_active']);
    }

    public function formSections(): HasMany
    {
        return $this->hasMany(FormSection::class);
    }

    public function fieldGroups(): HasMany
    {
        return $this->hasMany(FieldGroup::class);
    }

    public function formFields(): HasMany
    {
        return $this->hasMany(FormField::class);
    }

    public function scopeWhereHasEntityType($query, EntityType|int $entity_type): void
    {
        $entity_type_id = $entity_type instanceof EntityType ? $entity_type->id : $entity_type;
        $query->whereHas('entityTypes', fn ($q) => $q->where('entity_types.id', $entity_type_id));
    }

    public function scopeUserVisible($query, ?\Javaabu\Auth\User $user = null): void
    {
        $user = $user ?? auth()->user();
        $view_any_codes = self::getViewAnyCodes($user);
        if ($view_any_codes) {
            $query->whereIn('code', $view_any_codes);

            return;
        }

        $query->where('application_types.id', -1);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('description_images')
             ->acceptsFile(function (File $file) {
                 return AllowedMimeTypes::isAllowedMimeType($file->mimeType, 'image');
             });
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('large')
             ->width(1200)
             ->height(1200)
             ->fit('max', 1200, 1200)
             ->nonQueued()
             ->performOnCollections('description_images');
    }

    public function getAdminUrlAttribute(): string
    {
        return route('admin.application-types.edit', $this);
    }

    public function url(string $action = 'show'): string
    {
        return match ($action) {
            'index'  => route('admin.application-types.index'),
            'create' => route('admin.application-types.create'),
            default  => route("admin.application-types.$action", $this),
        };
    }

    public function render(Applicant $entity, Collection|null $form_inputs = null, bool $with_admin_sections = false): string
    {
        $form_sections = $this->formSections;
        if (! $with_admin_sections) {
            $form_sections = $form_sections->where('is_admin_section', false);
        }

        $form_sections = $form_sections->sortBy('order_column');
        $form_fields = $this->formFields->sortBy('order_column');

        $html = '';

        /** @var FormSection $form_section */
        foreach ($form_sections as $form_section) {
            $section_form_field_ids = $form_fields->where('form_section_id', $form_section->id)->pluck('id');
            $section_inputs = $form_inputs->whereIn('form_field_id', $section_form_field_ids);
            $html .= $form_section->render($entity, $section_inputs);
        }

        return $html;
    }

    public function canMarkAsProcessed(Application $application): bool
    {
        return true;
    }

    public function getApplicationTypeClass(): ?string
    {
        $application_type_classes = config('paperless.application_types');
        foreach ($application_type_classes as $application_type_class) {
            if ((new $application_type_class())->getCode() == $this->code) {
                return $application_type_class;
            }
        }

        return null;
    }

    public function getApplicationTypeClassInstance(): IsAnApplicationType
    {
        return new ($this->getApplicationTypeClass())();
    }

    public function hasExtraViewsToRender(): bool
    {
        return count($this->getApplicationTypeClassInstance()->hasExtraViewsToRender()) >= 1;
    }

    public function isApplicationType($application_type_class): bool
    {
        $type_instance = new $application_type_class();

        $type_code = $type_instance->getCode();

        return $this->code === $type_code;
    }


    public function determineExtraViewsToRender(): array
    {
        $something = [];


    }



    public function requiresPayment(): bool
    {
        $this->loadMissing('services');

        return $this->services->where('pivot.is_applied_automatically', true)->isNotEmpty();
    }

    public function getManuallyRaisedPaymentServices()
    {
        $this->loadMissing('services');

        return $this->services->where('pivot.is_applied_automatically', false);
    }

    public function getAutomaticallyRaisedPaymentServices()
    {
        $this->loadMissing('services');

        return $this->services->where('pivot.is_applied_automatically', true);
    }

    public function getFormFieldLabels(): array
    {
        $this->loadMissing('formFields');

        return $this->formFields->pluck('name', 'id')->toArray();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
                         ->logExcept($this->hidden)
                         ->logOnly(static::$logAttributes);
    }

    /**
     * @throws \ReflectionException
     */
    public function getServiceClass()
    {
        return ApplicationTypeService::make($this);
    }
}
