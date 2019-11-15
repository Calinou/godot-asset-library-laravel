@extends('layouts.app')

@section('title', __('Administration panel'))

@section('content')
<div class="container">
  <h2 class="text-center text-2xl font-medium">
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
        <tr class="{{ $user->is_blocked ? 'bg-red-100' : 'bg-white' }}">

          <td class="border px-3 py-1 text-right">{{ $user->name }}</td>

          <td class="border px-3 py-1">
            <a class="link" href="mailto:{{ $user->email }}">
              {{ $user->email }}
            </a>
          </td>

          <td class="border px-3 py-1">
            @if ($user->is_blocked)
            <form method="POST" action="{{ route('admin.unblock', ['user' => $user]) }}">
              @csrf
              <button type="submit" class="button">
                <span class="fa fa-circle-o fa-fw mr-1 opacity-75"></span>
                {{ __('Unblock') }}
              </button>
            </form>
            @else
            <form method="POST" action="{{ route('admin.block', ['user' => $user]) }}">
              @csrf
              <button type="submit" class="button text-red-700">
                <span class="fa fa-ban fa-fw mr-1 opacity-75"></span>
                {{ __('Block') }}
              </button>
            </form>
            @endif
          </td>

        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
