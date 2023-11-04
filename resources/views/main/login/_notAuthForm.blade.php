<div class="col">
    <div class="row border-bottom d-flex align-items-center" style="height: 57px">
        <h5 class="text-center">Войти</h5>
    </div>
    <div class="row">
        <form method="POST" action="{{ route('login') }}" class="h-100 w-100">
            @csrf
            <ul class="nav nav-pills flex-column pt-3" id="playlists">
                <li class="nav-item mb-3">
                    <input id="email" type="email" class="form-control text-input @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email">

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="nav-item mb-3">
                    <input id="password" type="password" class="form-control text-input @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password" placeholder="Пароль">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="nav-item mb-3">
                    <div>
                        <input class="form-check-input text-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember">{{ __('Запомнить') }}</label>
                    </div>
                    <button type="submit" class="btn button w-100 mt-3">Войти</button>
                    <button type="button" class="btn button w-100 mt-3" data-bs-toggle="modal"
                        data-bs-target="#registerModal">
                        Зарегистрироваться
                    </button>
                </li>
            </ul>
        </form>
    </div>
</div>
