<?php

namespace Javaabu\Paperless\Domains\ApplicationTypes\Traits;

trait HasApplicationSpecificPermissions
{
    public static function getApplicationTypePermissions(): array
    {
        $application_type_permissions = [];
        $application_types = config('paperless.application_types');
        foreach ($application_types as $application_type) {
            $name = str((new $application_type())->getName() . ' application type')->lower();
            $slug = (new $application_type())->getSlug() . '_application_type';
            $permissions = [];
            foreach (self::getPermissionList() as $value => $label) {
                $updated_value = str($value)->replace('application_type', $slug)->__toString();
                $updated_label = str($label)->replace('application type', $name)->__toString();
                $permissions[$updated_value] = $updated_label;
            }

            $application_type_permissions[$slug] = $permissions;
        }

        return $application_type_permissions;
    }

    public function getApplicationTypePermissionSlug(): ?string
    {
        $application_type_class = $this->getApplicationTypeClass();

        return (new $application_type_class())->getSlug();
    }

    public static function getDynamicPermissionList(string $prefix, string $suffix = '_application_types'): array
    {
        $application_types = config('paperless.application_types');
        $permissions = [];
        foreach ($application_types as $application_type) {
            $slug = (new $application_type())->getSlug();
            $permissions[] = "{$prefix}{$slug}{$suffix}";
        }

        return $permissions;
    }

    public static function getViewAnyPermissionList(): array
    {
        return self::getDynamicPermissionList('view_any_', '_application_type');
    }

    public static function getAllAssignPermissionList(): array
    {
        $assign_any = self::getDynamicPermissionList('assign_user_any_', '_application_type');
        $assign = self::getDynamicPermissionList('assign_user_');

        return array_merge($assign_any, $assign);
    }

    public static function getViewPermissionList(): array
    {
        return self::getDynamicPermissionList('view_');
    }

    public static function getEditPermissionList(): array
    {
        return self::getDynamicPermissionList('edit_');
    }

    public static function getDeletePermissionList(): array
    {
        return self::getDynamicPermissionList('delete_');
    }

    public static function getForceDeletePermissionList(): array
    {
        return self::getDynamicPermissionList('force_delete_');
    }


    public function getViewPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "view_{$slug}_application_type";
    }

    public function getViewAnyPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "view_any_{$slug}_application_type";
    }

    public function getEditAnyPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "edit_any_{$slug}_application_type";
    }

    public function getEditPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "edit_{$slug}_application_type";
    }

    public function getDeleteAnyPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "delete_any_{$slug}_application_type";
    }

    public function getDeletePermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "delete_{$slug}_application_types";
    }

    public function getForceDeletePermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "force_delete_{$slug}_application_types";
    }

    public function getCancelAnyPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "cancel_any_{$slug}_application_type";
    }

    public function getCancelPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "cancel_{$slug}_application_types";
    }

    public function getVerifyAnyPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "verify_any_{$slug}_application_type";
    }

    public function getVerifyPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "verify_{$slug}_application_types";
    }

    public function getExtendEtaAnyPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "extend_any_{$slug}_application_type_eta";
    }

    public function getExtendEtaPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "extend_{$slug}_application_type_eta";
    }

    public function getPayAnyPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "pay_any_{$slug}_application_type";
    }

    public function getPayPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "pay_{$slug}_application_types";
    }

    public function getApproveAnyPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "approve_any_{$slug}_application_type";
    }

    public function getApprovePermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "approve_{$slug}_application_types";
    }

    public function getAssignUserAnyPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "assign_user_any_{$slug}_application_type";
    }

    public function getAssignUserPermissionAttribute(): string
    {
        $slug = $this->getApplicationTypePermissionSlug();

        return "assign_user_{$slug}_application_types";
    }

    public static function getViewAnyCodes(\Javaabu\Auth\User $user): array
    {
        $view_any_permissions = self::getViewAnyPermissionList();
        $view_any_codes = [];
        foreach ($view_any_permissions as $code => $permission) {
            if ($user->can($permission)) {
                $view_any_codes[] = $code;
            }
        }

        return $view_any_codes;
    }

    public static function getPermissionList(): array
    {
        $permissions = [
            'view_any_application_type'        => 'View any application type',
            'view_application_types'           => 'View application types',
            'edit_any_application_type'        => 'Edit any application type',
            'edit_application_types'           => 'Edit application types',
            'delete_any_application_type'      => 'Delete any application type',
            'delete_application_types'         => 'Delete application types',
            'force_delete_application_types'   => 'Force delete application types',
            'cancel_any_application_type'      => 'Cancel any application type',
            'cancel_application_types'         => 'Cancel application types',
            'verify_any_application_type'      => 'Verify any application type',
            'verify_application_type_types'    => 'Verify application types',
            'extend_any_application_type_eta'  => 'Extend any application type eta',
            'extend_application_type_eta'      => 'Extend application type eta',
            'approve_any_application_type'     => 'Approve any application type',
            'approve_application_types'        => 'Approve application types',
            'assign_user_any_application_type' => 'Assign user to any application types',
            'assign_user_application_types'    => 'Assign user to application types',
        ];

        if (config('paperless.relations.services')) {
            $permissions = array_merge($permissions, [
                'pay_any_application_type' => 'Pay any application type',
                'pay_application_types'    => 'Pay application types',
            ]);
        }

        return $permissions;
    }


}
