<?php

namespace Javaabu\Paperless\Database\Seeders;

use Illuminate\Database\Seeder;

class ApplicationTypesSeeder extends Seeder
{
    public function run(): void
    {
        $application_types = config('paperless.application_types');
        foreach ($application_types as $application_type) {
            (new $application_type())->seed();
        }
    }
}
