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
      {{ __('Log in to Godot Asset Library') }}
    </div>

    <section class="w-full max-w-xs mx-auto mt-8 mb-2 bg-white rounded shadow p-4">
      @component('components/form-input', [
        'type' => 'email',
        'name' => 'email',
        'label' => __('Email address'),
        'placeholder' => __('user@example.com'),
        'required' => true,
        'requiredImplicit' => true,
        'autofocus' => true,
      ])
      @endcomponent

      @component('components/form-input', [
        'type' => 'password',
        'name' => 'password',
        'label' => __('Password'),
        'placeholder' => __('password'),
        'required' => true,
        'requiredImplicit' => true,
      ])
      @if (Route::has('password.request'))
      @slot('labelSuffix')
      <a href="{{ route('password.request') }}" class="form-label link text-right" tabindex="1">
        {{ __('Forgot your password?') }}
      </a>
      @endslot
      @endif
      @endcomponent

      <button class="button button-primary w-full" type="submit">
        {{ __('Login') }}
      </button>
    </section>
  </form>
</div>
@endsection
