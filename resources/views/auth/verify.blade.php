@extends('layouts.app')

@section('content')
<div class="container">
  @if (session('resent'))
    <div class="bg-white p-3 rounded shadow mb-4" role="alert">
      {{ __('A fresh verification link has been sent to your email address.') }}
    </div>
  @endif

  {{ __('Before proceeding, please check your email for a verification link.') }}
  <form method="POST" action="{{ route('verification.resend') }}">
    @csrf
    {{ __("If you didn't receive the email,") }}
    <button class="link" type="submit">{{ __('click here to request another') }}</button>.
  </form>
</div>
@endsection
