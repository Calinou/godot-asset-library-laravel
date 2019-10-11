@extends('layouts.app')

@section('title', $asset->title)

@section('content')
<div class="container">
  <h1 class="text-xl font-medium">{{ $asset->title }}</h1>
  <h2 class="text-lg text-gray-600 mb-8">{{ __('by :author', ['author' => $asset->author->name]) }}</h2>

  <div class="mb-8">
    <a href="{{ $asset->download_url }}" class="button button-success">{{ __('Download') }}</a>
    <a href="{{ $asset->browse_url }}" class="button">{{ __('Source code') }}</a>
  </div>

  <p>{{ $asset->description }}</p>

  <hr class="my-6">
  <h3 class="font-medium mb-4">{{ __('Details') }}</h3>
  <ul class="text-sm">
    <li><strong>{{ __('Version:') }}</strong> {{ $asset->version_string }}</li>
    <li><strong>{{ __('Compatible with:') }}</strong> Godot {{ $asset->godot_version }}</li>
    <li><strong>{{ __('License:') }}</strong> {{ $asset->license_name }}</li>
  </ul>
</div>
@endsection
