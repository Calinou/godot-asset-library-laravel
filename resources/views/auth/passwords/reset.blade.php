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
      @component('components/form-input', [
        'type' => 'email',
        'name' => 'email',
        'label' => __('Email address'),
        'placeholder' => __('user@example.com'),
        'required' => true,
        'autofocus' => true,
      ])
      @endcomponent

      @component('components/form-input', [
        'type' => 'password',
        'name' => 'password',
        'label' => __('Password'),
        'required' => true,
        'minlength' => 8,
      ])
      {{ __('Must be at least 8 characters long.') }}
      @endcomponent

      @component('components/form-input', [
        'type' => 'password',
        'name' => 'password_confirmation',
        'label' => __('Confirm password'),
        'required' => true,
        'minlength' => 8,
      ])
      @endcomponent

      <button class="button button-primary w-full" type="submit" data-loading>
        {{ __('Reset password') }}
      </button>
    </section>
  </form>
</div>
@endsection
