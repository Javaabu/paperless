<?php

namespace Javaabu\Paperless\Domains\Applications\Traits;

trait HasStatusActions
{
    public function getApplicationTypeClass(): string
    {
        return $this->applicationType->getApplicationTypeClass();
    }

    public function getServiceClass(): ?string
    {
        if (! $this->getApplicationTypeClass()) {
            return null;
        }

        return (new ($this->getApplicationTypeClass()))->getServiceClass();
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
        if (! $this->getServiceClass()) {
            return null;
        }

        $service_class_instance = (new ($this->getServiceClass()));
        if (method_exists($service_class_instance, $function_name)) {
            return $service_class_instance->$function_name($this);
        }

        return null;
    }

    /*
     * To hook into this method, create a service class for the given
     * application type and implement the method doBeforeSubmitting,
     * make sure you add the service class to the application
     * type class. Additionally, you will receive the
     * application instance as an argument.
     * */
    public function doBeforeSubmitting(): void
    {
        $this->callServiceFunction('doBeforeSubmitting');
    }

    /*
     * To hook into this method, create a service class for the given
     * application type and implement the method doAfterSubmitting,
     * make sure you add the service class to the application
     * type class. Additionally, you will receive the
     * application instance as an argument.
     */
    public function doAfterSubmitting(): void
    {
        $this->callServiceFunction('doAfterSubmitting');
    }

    public function doBeforeMarkingAsCancelled(): void
    {
        $this->callServiceFunction('doBeforeMarkingAsCancelled');
    }

    public function doAfterMarkingAsCancelled(): void
    {
        $this->callServiceFunction('doAfterMarkingAsCancelled');
    }

    public function doBeforeMarkingAsIncomplete(): void
    {
        $this->callServiceFunction('doBeforeMarkingAsIncomplete');
    }

    public function doAfterMarkingAsIncomplete(): void
    {
        $this->callServiceFunction('doAfterMarkingAsIncomplete');
    }

    public function doBeforeResubmitting(): void
    {
        $this->callServiceFunction('doBeforeResubmitting');
    }

    public function doAfterResubmitting(): void
    {
        $this->callServiceFunction('doAfterResubmitting');
    }

    public function doBeforeMarkingAsRejected(): void
    {
        $this->callServiceFunction('doBeforeMarkingAsRejected');
    }

    public function doAfterMarkingAsRejected(): void
    {
        $this->callServiceFunction('doAfterMarkingAsRejected');
    }

    public function doBeforeUndoRejection(): void
    {
        $this->callServiceFunction('doBeforeUndoRejection');
    }

    public function doAfterUndoRejection(): void
    {
        $this->callServiceFunction('doAfterUndoRejection');
    }

    public function doBeforeUndoVerification(): void
    {
        $this->callServiceFunction('doBeforeUndoVerification');
    }

    public function doAfterUndoVerification(): void
    {
        $this->callServiceFunction('doAfterUndoVerification');
    }

    public function doBeforeMarkingAsVerified(): void
    {
        $this->callServiceFunction('doBeforeMarkingAsVerified');
    }

    public function doAfterMarkingAsVerified(): void
    {
        $this->callServiceFunction('doAfterMarkingAsVerified');
    }

    public function doBeforeApproval(): void
    {
        $this->callServiceFunction('doBeforeApproval');
    }

    public function doAfterApproval(): void
    {
        $this->callServiceFunction('doAfterApproval');
    }
}
