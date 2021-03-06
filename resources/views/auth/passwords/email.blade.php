@extends('layouts.app')

@section('title', __('Forgot your password?'))

@section('content')
<div class="container">
  <form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="text-center text-xl font-medium">
      {{ __('Forgot your password?') }}
    </div>

    <section class="w-full max-w-xs mx-auto mt-8 bg-white dark:bg-gray-800 rounded shadow p-4">
      @component('components/form-input', [
        'type' => 'email',
        'name' => 'email',
        'label' => __('Email address'),
        'placeholder' => __('user@example.com'),
        'required' => true,
        'autofocus' => true,
      ])
      @endcomponent

      <button class="button button-primary w-full" type="submit" data-loading>
        {{ __('Send password reset link') }}
      </button>
    </section>
  </form>
</div>
@endsection
