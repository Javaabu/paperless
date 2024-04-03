<?php

namespace Javaabu\Paperless\Database\Seeders;

use Illuminate\Database\Seeder;
use Javaabu\Paperless\Domains\EntityTypes\EntityType;

class EntityTypeSeeder extends Seeder
{
    public function run(): void
    {
        $entity_type_enum = config('paperless.entity_type_enum');
        foreach ($entity_type_enum::labels() as $slug => $name) {
            EntityType::updateOrCreate([
                'slug' => $slug,
            ], [
                'name' => $name,
            ]);
        }
    }
}
