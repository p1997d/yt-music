@auth
    @include('main.login._authForm')
@else
    @include('main.login._notAuthForm')
@endauth
