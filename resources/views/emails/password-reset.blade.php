@component('mail::message')
# Password Reset Request

Click on the button below to change your password

@component('mail::button', ['url' => 'http://localhost:4200/reset-password?token='.$token])
Reset Password
@endcomponent

Thanks,<br>
{{ _('Startev Team') }}

@endcomponent
