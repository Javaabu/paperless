<?php

namespace Javaabu\Paperless\Console\Commands\Actions;

class ReplacePlaceholders
{
    public function handle(string $content, string $name): string
    {
        $name = str($name)->slug('_')->singular()->lower()->toString();

        return str_replace(
            [
                '{{ MainClassName }}',
                '{{ application_type_slug }}',
                '{{ ServiceClassName }}',
                '{{ application_type_title }}',
                '{{ FieldDefinitionClassName }}',
                '{{ ApplicationTypeCategoryClass }}',
                '{{ ApplicationTypeCategorySlug }}',
                '{{ ApplicationTypeCategoryLabel }}',
            ],
            [
                str($name)->studly()->singular()->toString(),
                $name,
                str($name)->studly()->singular()->toString() . 'Service',
                str($name)->title()->replace('_', ' ')->toString(),
                str($name)->studly()->singular()->toString() . 'FieldDefinition',
                str($name)->studly()->singular()->toString(),
                str($name)->slug('_'),
                str($name)->title()->replace('_', ' ')->toString(),
            ],
            $content
        );
    }
}
