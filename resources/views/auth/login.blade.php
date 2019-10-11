@extends('layouts.app')

@section('title', __('Log in'))

@section('content')
<div class="container">
  <form method="POST" action="{{ route('login') }}">
    @csrf
    {{--
      Always remember the user as is increasingly being done on other websites.
      These days, people usually use private browsing mode for "one-off" navigation instead.
      This makes the UI simpler.
    --}}
    <input type="hidden" name="remember" value="on">

    <div class="text-center text-xl font-medium">
      Log in to Godot Asset Library
    </div>

    <section class="w-full max-w-xs mx-auto mt-8 bg-white rounded shadow p-4">
      <div class="mb-6">
        <label for="email" class="form-label">{{ __('Email address') }}</label>
        <input
          id="email"
          type="email"
          name="email"
          value="{{ old('email') }}"
          required
          autocomplete="email"
          autofocus
          class="form-input-text"
        >

        @error('email')
        <div role="alert" class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>

      <div class="mb-8">
        <div class="flex items-center justify-between">
          <label for="password" class="form-label">{{ __('Password') }}</label>

          @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="form-label link text-right" tabindex="1">
            {{ __('Forgot your password?') }}
          </a>
          @endif
        </div>

        <input
          id="password"
          type="password"
          name="password"
          required
          autocomplete="current-password"
          class="form-input-text"
        >

        @error('password')
        <div role="alert" class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>

      <button class="button button-primary w-full" type="submit">
        {{ __('Login') }}
      </button>
    </section>
  </form>
</div>
@endsection
