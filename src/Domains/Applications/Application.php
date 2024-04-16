<?php

namespace Javaabu\Paperless\Domains\Applications;

use Javaabu\Auth\User;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Support\Collection;
use Javaabu\Paperless\Models\Payment;
use Javaabu\Paperless\Models\Countries;
use Javaabu\Paperless\Models\FormInput;
use Illuminate\Database\Eloquent\Model;
use Javaabu\Paperless\Models\PublicUser;
use Spatie\MediaLibrary\InteractsWithMedia;
use Javaabu\Paperless\Models\IndividualData;
use Illuminate\Database\Eloquent\SoftDeletes;
use Javaabu\Paperless\Models\AllowedMimeTypes;
use Javaabu\StatusEvents\Interfaces\Trackable;
use Javaabu\Paperless\Models\ApplicationStatus;
use Javaabu\Paperless\Enums\ApplicationStatuses;
use Javaabu\StatusEvents\Traits\HasStatusEvents;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Javaabu\Paperless\Support\Components\Section;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Javaabu\Paperless\Models\FirstOrCreateIndividualAction;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;
use Javaabu\Paperless\Support\InfoLists\Components\DocumentLister;
use function Javaabu\Paperless\Models\dnr;

class Application extends Model implements HasMedia, Trackable
{
    use HasStatusEvents;
    use InteractsWithMedia;
    use SoftDeletes;

    protected string $reference_number_format = 'APP-:year-:no';

    protected $fillable = [];

    protected array $searchable = [];

    protected $attributes = [
        'status' => ApplicationStatuses::DRAFT,
    ];

    protected $casts = [
        'eta_at'       => 'datetime',
        'alert_at'     => 'datetime',
        'submitted_at' => 'datetime',
        'verified_at'  => 'datetime',
        'approved_at'  => 'datetime',
    ];

    public function casts(): array
    {
        return [
            'status' => ApplicationStatuses::class,
        ];
    }

    public function applicationType(): BelongsTo
    {
        return $this->belongsTo(ApplicationType::class);
    }

    public function applicant(): MorphTo
    {
        return $this->morphTo();
    }

    public function publicUser(): BelongsTo
    {
        return $this->belongsTo(PublicUser::class);
    }

    public function formInputs(): HasMany
    {
        return $this->hasMany(related: FormInput::class);
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by_id');
    }

    public function payments(): MorphMany
    {
        return $this->morphMany(Payment::class, 'payable');
    }

    public function generated(): MorphTo
    {
        return $this->morphTo();
    }

    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', '%' . $search . '%');
    }

    public function scopeUserVisible($query, ?\Javaabu\Auth\User $user = null): void
    {
        $user = $user ?? auth()->user();

        $query
            ->whereHas('applicationType', function ($query) use ($user) {
                $query->userVisible($user);
            })
            ->orWhere('assigned_to_id', $user->id);
    }

    public function scopePending($query): void
    {
        $query->whereIn('status', [
            ApplicationStatuses::PendingVerification,
            ApplicationStatuses::Processing,
            ApplicationStatuses::PendingPayment,
            ApplicationStatuses::PendingApproval,
        ]);
    }

    public function scopeOngoing($query): void
    {
        $query->whereIn('status', [
            ApplicationStatuses::Draft,
            ApplicationStatuses::PendingVerification,
            ApplicationStatuses::Incomplete,
            ApplicationStatuses::Processing,
            ApplicationStatuses::PendingPayment,
            ApplicationStatuses::PendingApproval,
        ]);
    }

    public function scopeCompleted($query): void
    {
        $query->whereIn('status', [
            ApplicationStatuses::Rejected,
            ApplicationStatuses::Complete,
        ]);
    }

    public function scopeCancelled($query): void
    {
        $query->where('status', ApplicationStatuses::Cancelled);
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
            // $field_group->getBuilder()->saveInputs($this, $field_group, $validated_data); // this is the correct flow, implement it later when you aren't dying of stress
            $data = $validated_data[$field_group->slug] ?? [];
            $maldives_id = Countries::getMaldivesId();
            $students_data = collect($data);
            $student_field = $field_group->formFields->where('slug', 'student')->first();
            $certificate_number_field = $field_group->formFields->where('slug', 'certificate_number')->first();


            foreach ($students_data as $key => $student_data) {
                $individual_data = $student_data['nationality'] ?? -1;
                if ($individual_data != $maldives_id) {
                    $individual_data = IndividualData::fromArray([
                        'name'           => $student_data['name'],
                        'name_dv'        => $student_data['name_dv'],
                        'gov_id'         => $student_data['gov_id'],
                        'nationality_id' => $student_data['nationality'],
                        'gender'         => $student_data['gender'],
                    ]);
                } else {
                    $citizen_data = dnr()->getCitizenByIdAndName($student_data['gov_id'], $student_data['name'])->toDto();
                    $individual_data = IndividualData::fromCitizenData($citizen_data);
                }

                $individual = (new FirstOrCreateIndividualAction())->handle($individual_data);

                $form_input = $this->formInputs()
                                   ->where('form_field_id', $student_field->id)
                                   ->where('field_group_id', $field_group->id)
                                   ->where('group_instance_number', $key)
                                   ->first();

                if (! $form_input) {
                    $form_input = new FormInput();
                    $form_input->application()->associate($this);
                    $form_input->formField()->associate($student_field);
                    $form_input->fieldGroup()->associate($field_group);
                    $form_input->group_instance_number = $key;
                }

                $form_input->value = $individual->id;
                $form_input->save();


                $certificate_form_input = $this->formInputs()
                                             ->where('form_field_id', $certificate_number_field->id)
                                             ->where('field_group_id', $field_group->id)
                                             ->where('group_instance_number', $key)
                                             ->first();

                if (! $certificate_form_input) {
                    $certificate_form_input = new FormInput();
                    $certificate_form_input->application()->associate($this);
                    $certificate_form_input->formField()->associate($certificate_number_field);
                    $certificate_form_input->fieldGroup()->associate($field_group);
                    $certificate_form_input->group_instance_number = $key;
                }

                $certificate_form_input->value = $student_data['certificate_number'] ?? null;
                $certificate_form_input->save();
            }
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

    public function canBeAccessedByPublicUser(PublicUser $public_user): bool
    {
        $this->loadMissing('applicant');
        return $public_user->id === $this->public_user_id
            || (
                $this->applicant_type == 'individual'
                && $public_user->id === $this->applicant->id
            );
    }


    public function statusAction(): ApplicationStatus
    {
        $action_class = $this->status->getStatusAction();
        return new $action_class($this);
    }

    public function getStatusColors(): array
    {
        return ApplicationStatuses::colors();
    }

    public function getStatusLabels(): array
    {
        return ApplicationStatuses::labels();
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

}
