@extends('layouts.app')

@section('title', __('Choose a new password'))

@section('content')
<div class="container">
  <form method="POST" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="text-center text-xl font-medium">
      {{ __('Choose a new password') }}
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
          class="form-input-text"
        >
        @error('email')
        <div role="alert" class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>

      <div class="mb-6">
        <label for="password" class="form-label">{{ __('Password') }}</label>
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

      <div class="mb-8">
        <label for="password-confirm" class="form-label">{{ __('Confirm password') }}</label>
        <input
          id="password-confirm"
          type="password"
          name="password_confirmation"
          required
          autocomplete="new-password"
          class="form-input-text"
        >
      </div>

      <button class="button button-primary w-full" type="submit">
        {{ __('Reset password') }}
      </button>
    </section>
  </form>
</div>
@endsection
