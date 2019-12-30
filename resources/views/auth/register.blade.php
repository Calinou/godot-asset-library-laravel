@extends('layouts.app')
@inject('userClass', 'App\User')

@section('title', __('Sign up'))

@section('content')
<div class="container">
  <form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="text-center text-xl font-medium">
      {{ __('Sign up to :appName', ['appName' => config('app.name')]) }}
    </div>

    <section class="w-full max-w-xs mx-auto mt-8 bg-white dark:bg-gray-800 rounded shadow p-4">
        @component('components/form-input', [
          'name' => 'username',
          'label' => __('Username'),
          'placeholder' => __('Username'),
          'required' => true,
          'maxlength' => $userClass::USERNAME_MAX_LENGTH,
          'autofocus' => true,
          'autocomplete' => 'username',
        ])
        {{ __('Can only contain alphanumeric characters, underscores and dashes. Must not begin with a number, underscore or dash.') }}
        @endcomponent

        @component('components/form-input', [
          'type' => 'email',
          'name' => 'email',
          'label' => __('Email address'),
          'placeholder' => __('user@example.com'),
          'required' => true,
        ])
        {{ __('Will not be displayed publicly.') }}
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
