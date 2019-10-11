@extends('layouts.app')

@section('title', __('Sign up'))

@section('content')
<div class="container">
  <form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="text-center text-xl font-medium">
      {{ __('Sign up to Godot Asset Library') }}
    </div>

    <section class="w-full max-w-xs mx-auto mt-8 bg-white rounded shadow p-4">
      <div class="mb-6">
        <label for="name" class="form-label">{{ __('Name') }}</label>
        <input
          id="name"
          type="name"
          name="name"
          value="{{ old('name') }}"
          required
          autocomplete="name"
          autofocus
          class="form-input-text"
        >
        @error('name')
        <div role="alert" class="form-error">
          {{ $message }}
        </div>
        @enderror
      </div>

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
        {{ __('Sign up') }}
      </button>
    </section>
  </form>
</div>
@endsection
