@extends('paperless::admin.applications.applications')

@section('inner-content')
   @include('paperless::admin.applications.documents._documents')
   @include('paperless::admin.applications.documents._additional-documents')

   <x-forms::button-group :inline="true" class="d-flex justify-content-between mt-0">
       <x-forms::form method="PATCH" :model="$application" :action="route('admin.applications.status-update', $application)">
           <x-forms::submit color="dark" class="btn--icon-text btn--raised">
               <i class="zmdi zmdi-block"></i> {{ __('Cancel Application') }}
           </x-forms::submit>
       </x-forms::form>

       <div class="d-flex">
           <x-forms::link-button color="dark" class="btn--icon-text btn--raised" :url="route('admin.applications.edit', $application)">
               <i class="zmdi zmdi-arrow-left"></i> {{ __('Previous') }}
           </x-forms::link-button>
           <x-forms::link-button color="success" class="btn--icon-text btn--raised" :url="route('admin.applications.review', $application)">
               <span class="mr-2">{{ __( 'Save & Continue') }}</span>
               <i class="zmdi zmdi-arrow-right"></i>
           </x-forms::link-button>
       </div>
   </x-forms::button-group>
@endsection
