@component('mail::message')
Hi {{\Illuminate\Support\Str::of($user->email)->before('@')}},

Your account has been created. Use the button below to confirm your email:
<p>
    <i>This verification link will be valid for 24 hours.</i>
</p>

@component('mail::button', ['url' => $action_url])
Confirm
@endcomponent

Thanks,<br>
The Simple-Auth Team

-----

<small>If you’re having trouble with the button above, copy and paste the URL below into your web browser.</small>
<a href="{{$action_url}}">{{$action_url}}</a>

@endcomponent
