@extends('layouts.app')

@section('title', $asset->title)
@section('description', $asset->blurb)
@section('image', count($asset->previews) >= 1 ? $asset->previews[0]->link : '')

@section('content')
<div class="container">

  @if ($asset->is_archived)
  @component('components/alert', [
    'type' => 'warning',
  ])
  {{ __("This asset is marked as archived by its author. No further updates will be provided.") }}
  @endcomponent
  @endif

  @if (!$asset->is_published)
  @component('components/alert', [
    'type' => 'warning',
  ])
  {{ __("This asset has been unpublished. It won't be visible by other users until it's made public by an administrator.") }}
  @endcomponent
  @endif

  <div class="lg:flex lg:-mx-6">
    <div class="lg:w-1/2 lg:px-6">
      <div class="flex mb-5">
        <div class="flex-shrink-0 self-center">
          <img class="object-cover w-26 h-26 bg-gray-400 dark:bg-gray-700 rounded" src="{{ $asset->icon_url }}">
        </div>
        <div class="ml-6">
          <h1 class="text-xl font-medium">{{ $asset->title }}</h1>
          <h2 class="text-lg text-gray-600 mb-2">
            {{ __('by') }}
            <a href="{{ route('user.show', $asset->author) }}" class="link">
              {{ $asset->author->username }}
            </a>
          </h2>
          <a href="{{ route('asset.index', ['category' => $asset->category_id]) }}">
            <span class="tag tag-link font-bold text-sm">
              <span class="fa {{ $asset->category_icon }} fa-fw mr-1 -ml-1 opacity-75"></span>
              {{ $asset->category }}
            </span>
          </a>
        </div>
      </div>

      @if ($asset->tags)
      <div class="mt-5 mb-6 -ml-1 text-sm">
        @foreach ($asset->tags as $tag)
        <a href="{{ route('asset.index', ['filter' => $tag]) }}">
          <span class="tag tag-link">
            {{ $tag }}
          </span>
        </a>
        @endforeach
      </div>
      @endif

      @if ($asset->blurb)
      <h2 class="text-lg font-medium mb-6">
        {{ $asset->blurb }}
      </h2>
      @endif

      <div class="content">
        {{-- The HTML description is already sanitized by the Markdown parser that generates it --}}
        {!! $asset->html_description !!}
      </div>

      <div class="mt-6 text-gray-600 dark:text-gray-500 leading-relaxed">
        <div>
          <span class="fa fa-fw fa-gavel mr-1 opacity-75"></span>
          <strong>{{__('License:') }}</strong>
          {{ $asset->license_name }}
        </div>
        <div>
          <span class="fa fa-fw fa-newspaper-o mr-1 opacity-75"></span>
          <strong>{{__('Latest version:') }}</strong>
          {{ $asset->version_string }}
          ({{ __('released') }} @include('includes/date-relative', ['date' => \Carbon\Carbon::parse($asset->versions->last()->created_at)]))
        </div>
      </div>

      <details>
        {{-- Precise positioning --}}
        <summary
          class="cursor-pointer text-gray-600 dark:text-gray-500 leading-relaxed mb-4 ml-2 hover:underline"
        >{{ __('Version history') }}</summary>
        <table class="w-full shadow rounded text-sm bg-white dark:bg-gray-800">
          <thead>
            <tr class="font-bold">
              <td class="border px-3 py-1 text-right">{{ __('Version') }}</td>
              <td class="border px-3 py-1">{{ __('Released') }}</td>
              <td class="border px-3 py-1">{{ __('Compatible with') }}</td>
            </tr>
          </thead>
          <tbody>
            @foreach ($asset->versions as $version)
            <tr>
              <td class="border px-3 py-1 text-right">
                <a href="{{ $version->getDownloadUrlAttribute($asset->browse_url) }}" class="link">
                  {{ $version->version_string }}
                </a>
              </td>
              <td class="border px-3 py-1">@include('includes/date-relative', ['date' => \Carbon\Carbon::parse($version->created_at)])</td>
              <td class="border px-3 py-1">Godot {{ $version->godot_version }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </details>

      <div class="mt-8 mb-6 text-sm">
        <a href="{{ $asset->download_url }}" rel="nofollow" class="button button-success font-bold mr-1 mb-2">
          <span class="fa fa-download mr-1"></span>
          {{ __('Download') }}
        </a>
        <a href="{{ $asset->browse_url }}" rel="nofollow" class="button button-secondary mr-1 mb-2">
          <span class="fa fa-code mr-1"></span>
          {{ __('Source code') }}
        </a>
        <a href="{{ $asset->issues_url }}" rel="nofollow" class="button button-secondary mr-1 mb-2">
          <span class="fa fa-exclamation-circle mr-1 opacity-75"></span>
          {{ __('Submit an issue') }}
        </a>
        @if (!empty($asset->changelog_url))
        <a href="{{ $asset->changelog_url }}" rel="nofollow" class="button button-secondary mr-1">
          <span class="fa fa-newspaper-o mr-1 opacity-75"></span>
          {{ __('Changelog') }}
        </a>
        @endif
        @if (!empty($asset->donate_url))
        <a href="{{ $asset->donate_url }}" rel="nofollow" class="button button-secondary">
          <span class="fa fa-heart mr-1 opacity-75"></span>
          {{ __('Donate') }}
        </a>
        @endif
      </div>

      @can('edit-asset', $asset)
      <div class="mb-4 text-sm">
        <a href="{{ route('asset.edit', ['asset' => $asset]) }}" class="button button-primary font-bold mr-1 mb-2">
          <span class="fa fa-pencil mr-1"></span>
          {{ __('Edit') }}
        </a>

        <form
          method="POST"
          action="{{ route($asset->is_archived ? 'asset.unarchive' : 'asset.archive', ['asset' => $asset]) }}"
          class="inline-block"
        >
          @csrf
          @method('PUT')
          <button type="submit" class="button button-secondary mr-1 mb-2">
            <span class="fa {{ $asset->is_archived ? 'fa-unlock' : 'fa-lock' }} mr-1 opacity-75"></span>
            {{ $asset->is_archived ? __('Unarchive') : __('Archive') }}
          </button>
        </form>

        @can('admin')
        <form
          method="POST"
          action="{{ route($asset->is_published ? 'asset.unpublish' : 'asset.publish', ['asset' => $asset]) }}"
          class="inline-block"
        >
          @csrf
          @method('PUT')
          <button type="submit" class="button button-secondary">
            <span class="fa {{ $asset->is_published ? 'fa-eye-slash' : 'fa-eye' }} mr-1 opacity-75"></span>
            {{ $asset->is_published ? __('Unpublish') : __('Publish') }}
          </button>
        </form>
        @endcan

      </div>
      @endcan
    </div>

    <div class="lg:w-1/2 lg:px-6">
      {{-- Large image display --}}
      @if (count($asset->previews) >= 1 && $asset->previews[0]->type_id === App\AssetPreview::TYPE_IMAGE)
      <a id="gallery-image-anchor" href="{{ $asset->previews[0]->link }}" target="_blank" rel="nofollow noopener noreferrer">
        <div class="relative pb-9/16 bg-gray-400 dark:bg-gray-700 rounded">
          <img
            id="gallery-image-big"
            src="{{ $asset->previews[0]->link }}"
            alt="{{ $asset->previews[0]->caption }}"
            class="absolute h-full w-full object-cover rounded"
          >
        </div>
      </a>

      {{-- Caption --}}
      <div id="gallery-image-caption" class="text-center text-gray-700 dark:text-gray-500 my-3">
        {{-- Use a non-breaking space to ensure consistent height if there is no caption --}}
        {{ $asset->previews[0]->caption ?: ' ' }}
      </div>
      @else
      <div class="flex items-center justify-center h-64 bg-gray-400 dark:bg-gray-800 rounded">
        <div class="text-lg text-gray-600 dark:text-gray-500">
          {{ __('No preview available') }}
        </div>
      </div>
      @endif

      {{-- Small image displays --}}
      @if (count($asset->previews) >= 2)
      <div class="flex justify-center mt-2 -mx-px">
        @foreach ($asset->previews as $preview)
        @if ($preview->type_id === App\AssetPreview::TYPE_IMAGE)
        <div class="w-1/4 px-px">
          <a href="{{ $preview->link }}" target="_blank" rel="nofollow noopener noreferrer">
            <div class="relative pb-9/16 bg-gray-400 dark:bg-gray-700 rounded">
              <img
                src="{{ $preview->thumbnail ?? $preview->link }}"
                alt="{{ $preview->caption }}"
                class="absolute h-full w-full object-cover rounded gallery-image-small @if ($loop->first) gallery-image-small-active @else gallery-image-small-inactive @endif"
                data-full-size="{{ $preview->link }}"
              >
            </div>
          </a>
        </div>
        @endif
        @endforeach
      </div>
      @endif
    </div>
  </div>

  @can('submit-review', $asset)
  <hr class="my-6">
  <h2 class="text-xl font-medium mb-8">
    {{ __('Leave a review') }}
  </h2>

  @include('includes/asset-review-form', [
    'editing' => false,
    'action' => route('asset.reviews.store', ['asset' => $asset]),
  ])

  @elseif (!Auth::check())
  <hr class="my-6">
  <a class="link" href="{{ route('login') }}">
    {{ __('Please log in to submit a review.') }}
  </a>
  @endcan

  <hr class="my-8">
  <h2 class="text-xl font-medium mb-8">
    {{ trans_choice(
      '{0} No reviews|{1} :count review|[2,*] :count reviews',
      $asset->reviews->count()
    ) }}
    <span class="ml-3 pl-5 border-l border-gray-400 {{ $asset->score_color }} dark:text-gray-400">
      <span class="fa mr-1 opacity-50 @if ($asset->score >= 0) fa-thumbs-up @else fa-thumbs-down @endif"></span>
      {{ $asset->score }}
    </span>

    @php
    $positiveReviewsCount = $asset->reviews->filter(function ($review) {
      return $review->is_positive;
    })->count();
    @endphp

    <span class="ml-16 text-sm text-blue-500">
      <span class="fa fa-chevron-circle-up fa-fw opacity-75"></span>
      {{ $positiveReviewsCount }}
    </span>
    <span class="ml-4 text-sm text-red-700">
      <span class="fa fa-chevron-circle-down fa-fw opacity-75"></span>
      {{-- Infer the number of negative reviews based on the total number of reviews --}}
      {{ $asset->reviews->count() - $positiveReviewsCount }}
    </span>
  </h2>

  @forelse ($asset->reviews as $review)
  @php
  $isOwnReview = Auth::user() && $review->author->id === Auth::user()->id;
  @endphp

  @if ($review->comment || $isOwnReview)
  {{-- Highlight the review posted by the current user --}}
  <article class="relative review px-4 md:px-6 pt-4 pb-5 my-4 rounded shadow md:w-3/4 xl:w-3/5 @if ($isOwnReview) bg-blue-100 dark:bg-blue-1000 @else bg-white dark:bg-gray-800 @endif">
    @can('edit-review', $review)
    {{-- Remove spacing between items --}}
    <div class="absolute top-0 right-0 mr-2 mt-2" style="font-size: 0">
      <button type="button" class="text-base button cursor-pointer" data-review-edit>
        <span class="fa fa-pencil opacity-50"></span>
      </button>
      <form
        class="inline-block"
        method="POST"
        action="{{ route('asset.reviews.destroy', ['asset_review' => $review]) }}"
      >
        @csrf
        @method('DELETE')

        <button type="submit" class="text-base button cursor-pointer">
          <span class="fa fa-times opacity-50"></span>
        </button>
      </form>
    </div>
    @endcan
    <div class="text-gray-600 dark:text-gray-500 mb-6">

      @if ($review->is_positive)
      <span class="font-bold text-blue-500 dark:text-blue-400">
        <span class="fa fa-chevron-circle-up fa-fw opacity-75"></span>
        {{ __('Recommended') }}
      </span>
      @else
      <span class="font-bold text-red-700 dark:text-red-600">
        <span class="fa fa-chevron-circle-down fa-fw opacity-75"></span>
        {{ __('Not recommended') }}</span>
      @endif

      <span class="hidden md:inline">—</span>
      <div class="ml-6 md:ml-0 md:inline">
        <a href="{{ route('user.show', $review->author) }}" class="link">{{ $review->author->username }}</a>
        {{ __('commented') }}
        @include('includes/date-relative', ['date' => \Carbon\Carbon::parse($review->created_at)])
        @if ($review->updated_at->notEqualTo($review->created_at))
        <div class="md:inline md:ml-2 text-sm opacity-75">
          ({{ __('edited') }} @include('includes/date-relative', ['date' => \Carbon\Carbon::parse($review->updated_at)]))
        </div>
        @endif
      </div>
    </div>
    <div class="content text-gray-700 dark:text-gray-400" data-review-comment>
      @if (!empty($review->html_comment))
      {!! $review->html_comment !!}
      @else
      <span class="opacity-50 italic">
        {{ __('No comment attached. (Only you can see this notice.)') }}
      </span>
      @endif
    </div>

    @can('edit-review', $review)
    <div class="hidden" data-review-edit-form>
      @include('includes/asset-review-form', [
        'editing' => true,
        'action' => route('asset.reviews.update', ['asset_review' => $review]),
        'value' => $review->comment,
      ])
    </div>
    @endcan

    @if ($review->reply)
    <div class="content px-4 py-3 mt-6 md:ml-8 bg-gray-300 dark:bg-gray-700 rounded relative text-sm">
      <div class="absolute border-gray-300 dark:border-gray-700 top-0 -mt-6 arrow-up"></div>
      <div class="font-bold text-gray-600 dark:text-gray-300 mb-1">
        {{ __('Reply from :author', ['author' => $asset->author->username]) }}
        <span class="ml-4 opacity-75">
          @include('includes/date-relative', ['date' => \Carbon\Carbon::parse($review->reply->created_at)])
        </span>
        @if ($review->reply->updated_at->notEqualTo($review->reply->created_at))
        <div class="md:inline md:ml-2 text-sm opacity-75">
          ({{ __('edited') }} @include('includes/date-relative', ['date' => \Carbon\Carbon::parse($review->reply->updated_at)]))
        </div>
        @endif
      </div>
      {!! $review->reply->html_comment !!}
    </div>
    @else
    @can('submit-review-reply', $review)
    <details>
      <summary class="inline-block ml-3 mt-3 px-2 py-1 link cursor-pointer">
        <span class="fa fa-reply fa-fw mr-1 opacity-75"></span>
        {{ __('Reply') }}
      </summary>

      <form method="POST" action="{{ route('asset.reviews.replies.store', ['asset_review' => $review]) }}" class="mt-6 ml-8">
        @csrf

        @component('components/form-input', [
          'type' => 'textarea',
          'name' => 'comment',
          'label' => __('Reply'),
          'placeholder' => __('Your reply to the comment above…'),
          'required' => true,
          'maxlength' => 2000,
          'autocomplete' => 'off',
          'class' => 'h-32',
        ])
        {{ __('Supports') }}
        <a
          class="link"
          href="https://guides.github.com/features/mastering-markdown/"
          target="_blank"
          rel="nofollow noopener noreferrer"
        >GitHub Flavored Markdown</a>.
        {{ __('Please follow the') }}
        <a
          class="link"
          href="https://godotengine.org/code-of-conduct"
          target="_blank"
          rel="nofollow noopener noreferrer"
        >{{ __('Code of Conduct') }}</a>
        {{ __('when writing your reply.') }}
        @endcomponent

        <button class="button button-primary mt-6" type="submit" data-loading>
          {{ __('Submit reply') }}
        </button>
      </form>
    </details>
    @endcan
    @endif
  </article>
  @endif
  @empty
  @can('submit-review', $asset)
  <div class="my-6 text-gray-600">
    {{ __('No reviews yet. Be the first to leave a review!') }}
  </div>
  @endcan
  @endforelse

</div>
@endsection
