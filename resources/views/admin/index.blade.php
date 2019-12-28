@extends('layouts.app')

@section('title', __('Administration panel'))

@section('content')
<div class="container">
  <h2 class="text-center text-2xl font-medium">
    {{ __('Administration panel') }}
  </h2>

  <div class="mt-8">
    <table class="w-full xl:w-3/4 mx-auto shadow rounded text-sm">
      <thead>
        <tr class="font-bold">
          <td class="bg-white border dark:bg-gray-800 px-3 py-1 text-right">{{ __('Registered') }}</td>
          <td class="bg-white border dark:bg-gray-800 px-3 py-1">{{ __('Name') }}</td>
          <td class="bg-white border dark:bg-gray-800 px-3 py-1">{{ __('Assets') }}</td>
          <td class="bg-white border dark:bg-gray-800 px-3 py-1">{{ __('Reviews') }}</td>
          <td class="bg-white border dark:bg-gray-800 px-3 py-1">{{ __('Actions') }}</td>
        </tr>
      <tbody>
        @foreach ($users as $user)
        @php
        if ($user->id === Auth::user()->id) {
          $rowClasses = 'bg-blue-100 text-blue-800 dark:bg-blue-1000 dark:text-blue-200';
        } elseif ($user->is_blocked) {
          $rowClasses = 'bg-red-100 text-red-800 dark:bg-red-1000 dark:text-red-200';
        } else {
          $rowClasses = 'bg-white dark:bg-gray-800';
        }
        @endphp
        <tr class="{{ $rowClasses }}">
          <td class="border px-3 py-1 text-right">
            @include('includes/date-relative', ['date' => \Carbon\Carbon::parse($user->created_at)])
          </td>

          <td class="border px-3 py-1">
            <div
              @if ($user->is_admin)
              aria-label="{{ __('Administrator') }}"
              data-balloon-pos="up"
              data-balloon-blunt
              class="py-1"
              @endif
            >

              <a class="link" href="mailto:{{ $user->email }}"><span class="fa fa-envelope fa-fw mr-1 opacity-75"></span></a>
              @if ($user->is_admin)
              <span class="fa fa-shield fa-fw mr-1 text-yellow-600"></span>
              @endif
              <a class="link" href="{{ route('user.show', ['user' => $user]) }}">
                {{ $user->name }}
              </a>
            </div>
          </td>

          <td class="border px-3 py-1 @if ($user->assets->count() == 0) opacity-50 @endif">
            {{ $user->assets->count() }}
          </td>

          <td class="border px-3 py-1 @if ($user->assetReviews->count() == 0) opacity-50 @endif">
            {{ $user->assetReviews->count() }}
          </td>

          <td class="border px-3 py-1">
            @can('block-user', $user)
            @if ($user->is_blocked)
            <form method="POST" action="{{ route('admin.unblock', ['user' => $user]) }}">
              @csrf
              @method('PUT')
              <button type="submit" class="button button-sm">
                <span class="fa fa-circle-o fa-fw -ml-1 mr-1 opacity-75"></span>
                {{ __('Unblock') }}
              </button>
            </form>
            @else
            <form method="POST" action="{{ route('admin.block', ['user' => $user]) }}">
              @csrf
              @method('PUT')
              <button type="submit" class="button button-sm text-red-700">
                <span class="fa fa-ban fa-fw -ml-1 mr-1 opacity-75"></span>
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
