@component('mail::message')
Hi {{\Illuminate\Support\Str::of($user->email)->before('@')}},

Your account has been deleted.

See you soon,<br>
The Simple-Auth Team
@endcomponent
