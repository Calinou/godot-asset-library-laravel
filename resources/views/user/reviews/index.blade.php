@extends('layouts.app')

@section('title', __(":user's reviews", ['user' => $user->username]))

@section('content')
<div class="container px-0 sm:px-2">
  <h1 class="text-center text-2xl font-medium mb-8">
    {{ __(":user's reviews", ['user' => $user->username]) }}
  </h1>
  @if ($user->assetReviews->count() >= 1)
  <table class="w-full lg:w-3/4 xl:w-2/3 mx-auto shadow rounded text-sm">
    <thead>
      <tr class="font-bold">
        <td class="bg-white border dark:bg-gray-800 px-3 py-1 text-right">{{ __('Date') }}</td>
        <td class="bg-white border dark:bg-gray-800 px-3 py-1">{{ __('Asset') }}</td>
        <td class="bg-white border dark:bg-gray-800 px-3 py-1">{{ __('Review') }}</td>
      </tr>
    <tbody>
      @foreach ($user->assetReviews as $review)
      <tr class="bg-white dark:bg-gray-800">
        <td class="border px-3 py-1 text-right">
          @include('includes/date-relative', ['date' => \Carbon\Carbon::parse($review->created_at)])
        </td>

        <td class="border px-3 py-1">
          <a href="{{ route('asset.show', ['asset' => $review->asset]) }}" class="link">
            {{ $review->asset->title }}
          </a>
        </td>

        <td class="border px-3 py-1">
          @if ($review->is_positive)
          <span class="font-bold text-blue-500 dark:text-blue-400">
            <span class="-ml-1 fa fa-chevron-circle-up fa-fw opacity-75"></span>
            {{ __('Recommended') }}
          </span>
          @else
          <span class="font-bold text-red-700 dark:text-red-600">
            <span class="-ml-1 fa fa-chevron-circle-down fa-fw opacity-75"></span>
            {{ __('Not recommended') }}</span>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  @else
  <div class="mt-8 text-lg text-center text-gray-600">
    {{ __("This user hasn't posted any reviews yet.") }}
  </div>
  @endif
</div>
@endsection
