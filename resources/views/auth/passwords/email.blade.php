@extends('layouts.app')

@section('content')
@if (session('status'))
  <div role="alert">
    {{ session('status') }}
  </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
  @csrf

  <label for="email">{{ __('Email address') }}</label>
  <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

  @error('email')
    <span role="alert">
      <strong>{{ $message }}</strong>
    </span>
  @enderror

  <button type="submit">
    {{ __('Send Password Reset Link') }}
  </button>
</form>
@endsection
