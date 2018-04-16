@component('mail::message')
# {{ __('email.registration_confirmation.heading') }}

{{ __('email.registration_confirmation.text', ['name' => $user->name]) }}

@component('mail::button', ['url' => $user->getConfirmationLink()])
{{ __('email.registration_confirmation.confirm', ['name' => $user->name]) }}
@endcomponent

{{ __('email.registration_confirmation.footer') }}
{{ config('app.name') }}
@endcomponent
