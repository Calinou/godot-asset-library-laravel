@extends('layouts.app')
@inject('assetPreviewClass', 'App\AssetPreview')

@section('title', $asset->title)

@section('content')
<div class="container">
  <div class="lg:flex lg:-mx-6">

    <div class="lg:w-1/2 lg:px-6">
      <h1 class="text-xl font-medium">{{ $asset->title }}</h1>
      <h2 class="text-lg text-gray-600 mb-8">
        {{ __('by :author', ['author' => $asset->author->name]) }}
      </h2>

      <div class="mb-8">
        <a href="{{ $asset->download_url }}" rel="nofollow" class="button button-success">{{ __('Download') }}</a>
        <a href="{{ $asset->browse_url }}" rel="nofollow" class="button">{{ __('Source code') }}</a>
      </div>

      @if ($asset->blurb)
      <h2 class="text-lg font-medium mb-8">
        {{ $asset->blurb }}
      </h2>
      @endif

      <p>{{ $asset->description }}</p>

      <hr class="my-6">
      <h3 class="font-medium mb-4">{{ __('Details') }}</h3>
      <ul class="text-sm">
        <li><strong>{{ __('Version:') }}</strong> {{ $asset->version_string }}</li>
        <li><strong>{{ __('Compatible with:') }}</strong> Godot {{ $asset->godot_version }}</li>
        <li><strong>{{ __('License:') }}</strong> {{ $asset->license_name }}</li>
      </ul>
    </div>

    <div class="lg:w-1/2 lg:px-6">
      {{-- Large image display --}}
      @if (count($asset->previews) >= 1 && $asset->previews[0]->type_id === $assetPreviewClass::TYPE_IMAGE)
      <a id="gallery-image-anchor" href="{{ $asset->previews[0]->link }}" target="_blank" rel="nofollow noopener noreferrer">
        <div class="relative pb-9/16 bg-gray-400 rounded">
          <img
            id="gallery-image-big"
            src="{{ $asset->previews[0]->link }}"
            alt="{{ $asset->previews[0]->caption }}"
            class="absolute h-full w-full object-cover rounded"
          >
        </div>
      </a>
      @else
      <div class="flex items-center justify-center h-64 bg-gray-400 rounded">
        <div class="text-lg text-gray-600">
          {{ __('No preview available') }}
      </div>
      @endif

      {{-- Small image displays --}}
      @if (count($asset->previews) >= 2)
      <div class="flex justify-center mt-2 -mx-px">
        @foreach ($asset->previews as $preview)
        @if ($preview->type_id === $assetPreviewClass::TYPE_IMAGE)
        <div class="w-1/4 px-px">
          <a href="{{ $preview->link }}" target="_blank" rel="nofollow noopener noreferrer">
            <div class="relative pb-9/16 bg-gray-400 rounded">
              <img
                src="{{ $preview->thumbnail }}"
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
</div>
@endsection
