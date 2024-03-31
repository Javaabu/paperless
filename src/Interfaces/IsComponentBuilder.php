<?php

namespace Javaabu\Paperless\Interfaces;

interface IsComponentBuilder
{
    public function getDefaultValidationRules(Applicant $applicant, ?array $request_data = []): array;

}
