<?php

namespace Javaabu\Paperless\Support\Builders;

use Javaabu\Paperless\Interfaces\Applicant;
use Javaabu\Paperless\Interfaces\IsComponentBuilder;
use Javaabu\Paperless\Support\Components\TextareaInput;
use Javaabu\Paperless\Support\Components\RepeatingGroup;

class TextareaBuilder extends ComponentBuilder implements IsComponentBuilder
{
    public static string $value = 'textarea';

    public function render(?string $input = null, int | null $instance = null)
    {
        return TextareaInput::make($this->form_field->slug)
                            ->label($this->form_field->name)
                            ->markAsRequired($this->form_field->is_required)
                            ->state($input)
                            ->repeatingGroup(function () {
                                if ($this->form_field->field_group_id) {
                                    return RepeatingGroup::make($this->form_field->fieldGroup->name)
                                                         ->id($this->form_field->fieldGroup->slug);
                                }

                                return null;
                            })
                       ->repeatingInstance($instance)
                       ->toHtml();
    }

    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = [], ?int $iteration = null): array
    {
        $is_required = $this->form_field->is_required ? 'required' : 'nullable';

        return [
            $this->form_field->slug => [$is_required, 'string', 'max:65535'],
        ];
    }
}
