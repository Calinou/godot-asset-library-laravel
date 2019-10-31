@extends('layouts.app')
@inject('userClass', 'App\User')

@section('title', __('Sign up'))

@section('content')
<div class="container">
  <form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="text-center text-xl font-medium">
      {{ __('Sign up to Godot Asset Library') }}
    </div>

    <section class="w-full max-w-xs mx-auto mt-8 bg-white rounded shadow p-4">
        @component('components/form-input', [
          'name' => 'name',
          'label' => __('Name'),
          'placeholder' => __('Nickname or full name'),
          'required' => true,
          'maxlength' => $userClass::NAME_MAX_LENGTH,
          'autofocus' => true,
          'autocomplete' => 'username',
        ])
        @endcomponent

        @component('components/form-input', [
          'type' => 'email',
          'name' => 'email',
          'label' => __('Email address'),
          'placeholder' => __('user@example.com'),
          'required' => true,
        ])
        @endcomponent

        @component('components/form-input', [
          'type' => 'password',
          'name' => 'password',
          'label' => __('Password'),
          'required' => true,
          'minlength' => $userClass::PASSWORD_MIN_LENGTH,
        ])
        {{ __('Must be at least :passwordMinLength characters long.', ['passwordMinLength' => $userClass::PASSWORD_MIN_LENGTH]) }}
        @endcomponent

        @component('components/form-input', [
          'type' => 'password',
          'name' => 'password_confirmation',
          'label' => __('Confirm password'),
          'required' => true,
          'minlength' => $userClass::PASSWORD_MIN_LENGTH,
        ])
        @endcomponent

      <button class="button button-primary w-full" type="submit" data-loading>
        {{ __('Sign up') }}
      </button>
    </section>
  </form>
</div>
@endsection
