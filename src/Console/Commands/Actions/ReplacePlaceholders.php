<?php

namespace Javaabu\Paperless\Console\Commands\Actions;

class ReplacePlaceholders
{
    public function handle(string $content, string $name): string
    {
        return str_replace(
            [
                '{{ MainClassName }}',
                '{{ application_type_slug }}',
                '{{ ServiceClassName }}',
                '{{ application_type_title }}',
                '{{ FieldDefinitionClassName }}',
            ],
            [
                str($name)->studly()->singular()->toString(),
                $name,
                str($name)->studly()->singular()->toString() . 'Service',
                str($name)->title()->replace('_', ' ')->toString(),
                str($name)->studly()->singular()->toString() . 'FieldDefinition',
            ],
            $content
        );
    }
}
