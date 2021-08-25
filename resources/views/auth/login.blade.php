@extends('layouts.auth', ['title' => 'Вход в систему'])

@section('content')
  <div class="auth">
    <div class="auth__wrap">
      <div class="auth__logo">
        <img src="{{ asset('images/logo.png') }}" alt="">
      </div>
      <div class="auth__entry">
          <form method="POST" action="{{ route('login') }}">
            @csrf
              <div class="form__group">
                <div class="form-group">
                  <label for="login" class="form-group__label">Имя пользователя</label>
                  <input id="login" type="text" class="form-control @error('user_login') is-invalid @enderror" name="user_login" value="{{ old('user_login') }}" required autofocus>
                </div>
              </div>
              <div class="form__group">
                <div class="form-group">
                  <label for="password" class="form-group__label">Пароль</label>
                  <input id="password" type="password" class="form-control @error('user_login') is-invalid @enderror" name="password" required autocomplete="current-password">
                  @error('user_login')
                  <span class="form__feedback" role="alert"><strong>{{ $message }}</strong></span>
                  @enderror
                </div>
            </div>
            <div class="auth__control">
              <div class="input-control">
                <input class="input-control__input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="input-control__label" for="remember">Запомнить</label>
              </div>
              <button type="submit" class="btn btn--primary">Войти</button>
            </div>
          </form>
      </div>
    </div>
  </div>
@endsection
