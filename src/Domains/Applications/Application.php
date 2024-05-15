<?php

namespace Javaabu\Paperless\Domains\Applications;

use Javaabu\Auth\User;
use Spatie\MediaLibrary\HasMedia;
use Spatie\ModelStates\HasStates;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Models\FormInput;
use Javaabu\Paperless\Models\FieldGroup;
use Illuminate\Database\Eloquent\Builder;
use Javaabu\Helpers\Media\AllowedMimeTypes;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Javaabu\StatusEvents\Interfaces\Trackable;
use Javaabu\StatusEvents\Traits\HasStatusEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Javaabu\Paperless\Support\Components\Section;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Javaabu\Paperless\StatusActions\Statuses\Draft;
use Javaabu\Paperless\Contracts\ApplicationContract;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Javaabu\Paperless\StatusActions\Statuses\Approved;
use Javaabu\Paperless\StatusActions\Statuses\Rejected;
use Javaabu\Paperless\StatusActions\Statuses\Verified;
use Javaabu\Paperless\StatusActions\Statuses\Cancelled;
use Javaabu\Paperless\StatusActions\Statuses\Incomplete;
use Javaabu\Helpers\AdminModel\{AdminModel, IsAdminModel};
use Javaabu\Paperless\StatusActions\Statuses\PendingVerification;
use Javaabu\Paperless\Support\InfoLists\Components\DocumentLister;
use Javaabu\Paperless\Domains\Applications\Traits\HasStatusActions;
use Javaabu\Paperless\Domains\Applications\Enums\ApplicationStatuses;
use Javaabu\Paperless\Exceptions\ApplicationStatusEnumDoesNotImplement;
use Javaabu\Paperless\Domains\Applications\Enums\IsApplicationStatusesEnum;

class Application extends Model implements HasMedia, Trackable, AdminModel, ApplicationContract
{
    use HasStates;
    use HasStatusActions;
    use HasStatusEvents;
    use InteractsWithMedia;
    use IsAdminModel;
    use SoftDeletes;

    protected string $reference_number_format = 'APP-:year-:no';

    protected $fillable = [];

    protected array $searchable = [];

    protected $casts = [
        'eta_at'       => 'datetime',
        'alert_at'     => 'datetime',
        'submitted_at' => 'datetime',
        'verified_at'  => 'datetime',
        'approved_at'  => 'datetime',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $status_class = config('paperless.application_status')::make(config('paperless.application_status_on_create'), $model);
            $model->status = $status_class;
        });
    }

    public function casts(): array
    {
        return [
            'status' => config('paperless.application_status'),
        ];
    }

    public function applicationType(): BelongsTo
    {
        return $this->belongsTo(config('paperless.models.application_type'));
    }

    public function applicant(): MorphTo
    {
        return $this->morphTo();
    }

    public function publicUser(): BelongsTo
    {
        return $this->belongsTo(config('paperless.models.user'));
    }

    public function formInputs(): HasMany
    {
        return $this->hasMany(related: FormInput::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(config('paperless.models.admin'), 'verified_by_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(config('paperless.models.admin'), 'approved_by_id');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(config('paperless.models.payment'), 'payable');
    }

    public function generated(): MorphTo
    {
        return $this->morphTo();
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeSearch($query, $search): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }

    public function scopeUserVisible($query, ?\Javaabu\Auth\User $user = null): void
    {
        $user = $user ?? auth()->user();

        $query
            ->whereHas('applicationType', function ($query) use ($user) {
                $query->userVisible($user);
            });
    }

    /**
     * @throws ApplicationStatusEnumDoesNotImplement
     */
    public function getApplicationStatusEnum(): string
    {
        $enum_class = config('paperless.enums.application_status');

        if (! in_array(IsApplicationStatusesEnum::class, class_implements($enum_class))) {
            throw new ApplicationStatusEnumDoesNotImplement();
        }

        return $enum_class;
    }

    public function scopePending(Builder $query): void
    {
        $query->whereIn('status', [
            ApplicationStatuses::Pending,
        ]);
    }

    /**
     * @throws ApplicationStatusEnumDoesNotImplement
     */
    public function scopeDraft(Builder $query): void
    {
        $query->whereIn('status', [
            $this->getApplicationStatusEnum()::Draft,
        ]);
    }

    /**
     * @throws ApplicationStatusEnumDoesNotImplement
     */
    public function scopeOngoing(Builder $query): void
    {
        $query->whereIn('status', [
            $this->getApplicationStatusEnum()::Draft,
            $this->getApplicationStatusEnum()::Pending,
        ]);
    }

    /**
     * @throws ApplicationStatusEnumDoesNotImplement
     */
    public function scopeCompleted($query): void
    {
        $query->whereIn('status', [
            $this->getApplicationStatusEnum()::Rejected,
            $this->getApplicationStatusEnum()::Approved,
        ]);
    }

    /**
     * @throws ApplicationStatusEnumDoesNotImplement
     */
    public function scopeCancelled($query): void
    {
        $query->where('status', $this->getApplicationStatusEnum()::Cancelled);
    }

    public function formattedId(): Attribute
    {
        return Attribute::get(function () {
            $reference_number_format = $this->reference_number_format;
            $reference_number_format = str_replace(':year', $this->created_at->format('y'), $reference_number_format);
            $formatted_id = str_pad($this->id, 3, '0', STR_PAD_LEFT);
            $reference_number_format = str_replace(':no', $formatted_id, $reference_number_format);

            return $reference_number_format;
        });
    }

    public function getAdminLinkNameAttribute(): string
    {
        return $this->formatted_id;
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('documents')
             ->acceptsMimeTypes(
                 AllowedMimeTypes::getAllowedMimeTypes(['document', 'image'])
             );
    }

    public function updateFormInputs(array $validated_data): void
    {
        $form_fields = $this->applicationType->formFields->whereNull('field_group_id');
        foreach ($form_fields as $form_field) {
            $form_field->getBuilder()->saveInputs($this, $form_field, $validated_data);
        }


        $field_groups = $this->applicationType->fieldGroups;
        foreach ($field_groups as $field_group) {
            $validated_field_group_data = data_get($validated_data, $field_group->slug, []);

            foreach ($validated_field_group_data as $iteration => $field_group_validated_data) {
                foreach ($field_group->formFields as $form_field) {
                    $form_field->getBuilder()->saveFieldGroupInputs($this, $form_field, $field_group, $iteration, $field_group_validated_data);
                }
            }

            // Delete all unused repeating group instances.
            $this
                ->formInputs()
                ->where('field_group_id', $field_group->id)
                ->where('group_instance_number', '>=', count($validated_field_group_data))
                ->delete();
        }
    }

    public function renderInfoList(): string
    {
        $form_sections = $this->applicationType->formSections->sortBy('order_column');
        $form_fields = $this->applicationType->formFields->sortBy('order_column');

        $html = '';
        foreach ($form_sections as $form_section) {
            $section_form_field_ids = $form_fields->where('form_section_id', $form_section->id)->pluck('id');
            $section_inputs = $this->formInputs->whereIn('form_field_id', $section_form_field_ids);
            $html .= $form_section->renderInfoList($this->applicant, $section_inputs);
        }

        return $html;
    }

    public function renderRequiredDocumentList()
    {
        $required_documents = $this->applicationType->documentTypes;
        if ($required_documents->isEmpty()) {
            return '';
        }

        $documents = $this->getMedia('documents');

        return $this->renderDocuments(
            $required_documents,
            $documents,
            __('Required Documents')
        );
    }

    public function renderAdditionalDocumentList()
    {
        $additional_documents = $this->getMedia('documents')->whereNull('document_type_id')->sortByDesc('created_at');
        if ($additional_documents->isEmpty()) {
            return '';
        }

        return $this->renderDocuments(
            null,
            $additional_documents,
            __('Additional Documents')
        );
    }

    public function renderDocuments(
        Collection|null $documents = null,
        Collection|null $uploaded_documents = null,
        string|null     $section_label = null
    ) {
        $documents_html = "";
        if ($documents) {
            foreach ($documents as $document) {
                $uploaded_document = $uploaded_documents->where('document_type_id', $document->id)->first();
                $documents_html .= DocumentLister::make($document->name)
                                                 ->markAsRequired($document?->pivot?->is_required)
                                                 ->size($uploaded_document?->human_readable_size)
                                                 ->fileName($uploaded_document?->file_name)
                                                 ->downloadLink($uploaded_document?->getFullUrl())
                                                 ->toHtml();
            }
        }

        if (! $documents && $uploaded_documents) {
            foreach ($uploaded_documents as $document) {
                $documents_html .= DocumentLister::make($document->name)
                                                 ->size($document->human_readable_size)
                                                 ->fileName($document->file_name)
                                                 ->downloadLink($document?->getFullUrl())
                                                 ->toHtml();
            }
        }

        return Section::make($section_label)
                      ->schema($documents_html)
                      ->toHtml();
    }

    public function canBeAccessedBy(User $user): bool
    {
        return $user->id == $this->assigned_to_id;
    }

    public function canBeAccessedByPublicUser($public_user): bool
    {
        $this->loadMissing('applicant');

        if ($public_user->id === $this->public_user_id) {
            return true;
        }

        if ($this->applicant_type == 'individual') {
            return $public_user->id === $this->applicant->id;
        }

        return false;
    }

    public function isProcessing(): bool
    {
        return in_array($this->status->getValue(), [
            PendingVerification::getMorphClass(),
            Verified::getMorphClass(),
            Incomplete::getMorphClass(),
        ]);
    }

    public function isNotProcessing(): bool
    {
        return ! $this->isProcessing()
            || $this->status->getValue() === Draft::getMorphClass();
    }

    public function isCompleted(): bool
    {
        return in_array($this->status->getValue(), [
            Approved::getMorphClass(),
            Cancelled::getMorphClass(),
            Rejected::getMorphClass(),
        ]);
    }

    public function isRejected()
    {
        return $this->status->getValue() === Rejected::getMorphClass();
    }

    public function getStatusColor(string $status): string
    {
        $action = config('paperless.application_status')::make($status, $this);

        return $action->getColor();
    }

    public function getStatusLabel(string $status): string
    {
        $action = config('paperless.application_status')::make($status, $this);

        return $action->getLabel();
    }

    public function generatedTypeLabel(): Attribute
    {
        return Attribute::get(function () {
            return match ($this->generated_type) {
                'license'           => __('License'),
                'certificate'       => __('Certificate'),
                'instructor_course' => __('Course Instructor'),
                default             => __('Generated')
            };
        });
    }

    public function relatedTypeLabel(): Attribute
    {
        return Attribute::get(function () {
            return match ($this->related_type) {
                'academy_instructor' => __('Academy Instructor'),
                default              => __('Related')
            };
        });
    }

    public function getFormInputValueForField(string $field_name): ?string
    {
        return $this->formInputs()
                    ->whereHas('formField', function ($query) use ($field_name) {
                        $query->where('slug', $field_name);
                    })
                    ->first()?->value;
    }

    public function getFormInputsForAllFieldGroups(): array
    {
        $field_groups = $this->applicationType->fieldGroups;

        // Form fields grouped by their field group id
        $form_fields_grouped = $this->applicationType
            ->formFields()
            ->whereIn('field_group_id', $field_groups->pluck('id'))
            ->get()
            ->groupBy('field_group_id');

        $form_inputs_for_repeating_groups = $this->formInputs()
                                                 ->whereIn('field_group_id', $form_fields_grouped->keys())
                                                 ->get()
                                                 ->groupBy('group_instance_number');

        $data = [];

        foreach ($field_groups as $field_group) {
            $field_group_slug = $field_group->slug;
            $form_fields = $form_fields_grouped->get($field_group->id);

            $group_instance_data = [];

            foreach ($form_inputs_for_repeating_groups as $group_instance_number => $form_inputs) {
                $field_group_data = [];

                foreach ($form_fields as $form_field) {
                    $form_input = $form_inputs->where('form_field_id', $form_field->id)->first();
                    $form_field_slug = $form_field->slug;
                    $form_field_value = $form_input->value;

                    $field_group_data[$form_field_slug] = $form_field_value;
                }

                $group_instance_data[] = $field_group_data;
            }

            $data[$field_group_slug] = $group_instance_data;
        }

        return $data;
    }

    public function getFormInputsForFieldGroup(FieldGroup $field_group)
    {
        return $this->formInputs()
                    ->where('field_group_id', $field_group)
                    ->get();
    }

    public function getAdminUrlAttribute(): string
    {
        return route('admin.applications.show', $this);
    }

    public function getAllPayments(): Collection
    {
        return $this->payments->merge($this->getApplicationTypeSpecificPayments());
    }

    #[\Override] public function getStatusColors(): array
    {
        // TODO: Implement getStatusColors() method.
    }

    #[\Override] public function getStatusLabels(): array
    {
        // TODO: Implement getStatusLabels() method.
    }
}
