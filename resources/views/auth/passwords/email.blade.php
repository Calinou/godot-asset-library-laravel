@extends('layouts.app')

@section('title', __('Forgot your password?'))

@section('content')
<div class="container">
  <form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="text-center text-xl font-medium">
      {{ __('Forgot your password?') }}
    </div>

    <section class="w-full max-w-xs mx-auto mt-8 bg-white rounded shadow p-4">
      <div class="mb-8">
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

      <button class="button button-primary w-full" type="submit">
        {{ __('Send password reset link') }}
      </button>
    </section>
  </form>
</div>
@endsection
