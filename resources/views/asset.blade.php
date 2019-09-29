@extends('layouts.app')

@section('title', 'Asset')

@section('content')
<div class="container">
  <h1>{{ $asset->title }}</h1>
  <h2>{{ __('by :author', ['author' => $asset->author->name]) }}</h2>
  <a href="{{ $asset->download_url }}">{{ __('Download') }}</a>
  <a href="{{ $asset->browse_url }}">{{ __('Source code') }}</a>
  <h3>{{ __('Description') }}</h3>
  <p>{{ $asset->description }}</p>
  <h3>{{ __('Details') }}</h3>
  <ul>
    <li><strong>{{ __('Version:') }}</strong> {{ $asset->version }}</li>
    <li><strong>{{ __('Compatible with:') }}</strong> Godot {{ $asset->version }}</li>
    <li><strong>{{ __('License:') }}</strong> {{ $asset->cost }}</li>
  </ul>
</div>
@endsection
