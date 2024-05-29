<?php

namespace Javaabu\Paperless\Domains\Applications\Traits;

trait HasStatusActions
{
    public function getApplicationTypeClass(): string
    {
        return $this->applicationType->getApplicationTypeClass();
    }

    public function callApplicationTypeClassFunction(string $function_name)
    {
        if (! $this->getApplicationTypeClass()) {
            return null;
        }

        $application_type_class_instance = (new ($this->getApplicationTypeClass()));
        if (method_exists($application_type_class_instance, $function_name)) {
            return $application_type_class_instance->$function_name($this);
        }

        return null;
    }

    public function callServiceFunction(string $function_name)
    {
        if (! $this->applicationType->getServiceClass()) {
            return null;
        }

        $service_class_instance = (new ($this->applicationType->getServiceClass()));
        if (method_exists($service_class_instance, $function_name)) {
            $fresh_application = $this->fresh();

            return $service_class_instance->$function_name($fresh_application);
        }

        return null;
    }
}
