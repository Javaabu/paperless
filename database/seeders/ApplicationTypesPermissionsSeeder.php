<?php

namespace Javaabu\Paperless\Database\Seeders;

use Illuminate\Database\Seeder;
use Javaabu\Paperless\Domains\ApplicationTypes\ApplicationType;

class ApplicationTypesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $application_permissions = ApplicationType::getApplicationTypePermissions();

        foreach ($application_permissions as $model => $permissions) {
            foreach ($permissions as $name => $desc) {
                $permission = \Spatie\Permission\Models\Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web_admin']);
                $permission->update(['description' => $desc, 'model' => $model]);
                $permission->save();
            }
        }
    }
}
