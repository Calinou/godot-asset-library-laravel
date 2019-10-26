@extends('layouts.app')

@section('title', __('Administration panel'))

@section('content')
<div class="container">
  <h2 class="text-center text-xl font-medium">
    {{ __('Administration panel') }}
  </h2>

  <div class="flex justify-center mt-8">
    <table class="shadow rounded text-sm">
      <thead>
        <tr class="font-bold">
          <td class="bg-white border px-3 py-1 text-right">{{ __('Name') }}</td>
          <td class="bg-white border px-3 py-1">{{ __('Email address') }}</td>
          <td class="bg-white border px-3 py-1">{{ __('Actions') }}</td>
        </tr>
      <tbody>
        @foreach ($users as $user)
        <tr>
          <td class="bg-white border px-3 py-1 text-right">{{ $user->name }}</td>
          <td class="bg-white border px-3 py-1">
            <a class="link" href="mailto:{{ $user->email }}">
              {{ $user->email }}
            </a>
          </td>
          <td class="bg-white border px-3 py-1"></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
