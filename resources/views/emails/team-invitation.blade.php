@component('mail::message')
{{ __('wirement-profile::default.mail.team_invitation.message.invitation', ['team' => $teamName]) }}

{{ __('wirement-profile::default.mail.team_invitation.message.instruction') }}

@component('mail::button', ['url' => $acceptUrl])
{{ __('wirement-profile::default.mail.team_invitation.label.accept_invitation') }}
@endcomponent

{{ __('wirement-profile::default.mail.team_invitation.message.notice') }}
@endcomponent
