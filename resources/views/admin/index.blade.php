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
        @php
        if ($user->id === Auth::user()->id) {
          $rowClasses = 'bg-blue-100 text-blue-800';
        } elseif ($user->is_blocked) {
          $rowClasses = 'bg-red-100 text-red-800';
        } else {
          $rowClasses = 'bg-white';
        }
        @endphp
        <tr class="{{ $rowClasses }}">
          <td class="border px-3 py-1 text-right">
            <div
              @if ($user->is_admin)
              aria-label="{{ __('Administrator') }}"
              data-balloon-pos="up"
              data-balloon-blunt
              class="py-1"
              @endif
            >
              @if ($user->is_admin)
              <span class="fa fa-shield fa-fw mr-1 text-yellow-600"></span>
              @endif
              {{ $user->name }}
            </div>
          </td>

          <td class="border px-3 py-1">
            <a class="link" href="mailto:{{ $user->email }}">
              {{ $user->email }}
            </a>
          </td>

          <td class="border px-3 py-1">
            @can('block-user', $user)
            @if ($user->is_blocked)
            <form method="POST" action="{{ route('admin.unblock', ['user' => $user]) }}">
              @csrf
              <button type="submit" class="button button-sm">
                <span class="fa fa-circle-o fa-fw mr-1 opacity-75"></span>
                {{ __('Unblock') }}
              </button>
            </form>
            @else
            <form method="POST" action="{{ route('admin.block', ['user' => $user]) }}">
              @csrf
              <button type="submit" class="button button-sm text-red-700">
                <span class="fa fa-ban fa-fw mr-1 opacity-75"></span>
                {{ __('Block') }}
              </button>
            </form>
            @endif
            @endcan
          </td>

        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection
