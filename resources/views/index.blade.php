@extends('layouts.app')
@inject('assetClass', 'App\Asset')

@section('title', __('Home'))

@section('content')
<div class="container">
  <h2 class="text-center text-xl font-medium">
    {{ __('Welcome to the Godot Asset Library') }}
  </h2>

  {{--
    We must append the query parameters so that search filters carry on
    when the user clicks a page number
  --}}
  {{ $assets->appends(Request::all())->links() }}

  <section class="flex flex-wrap -mx-2">
    @foreach ($assets->items() as $asset)
    <div class="w-full lg:w-1/2 px-2 my-2">
      <a href="{{ route('asset.show', ['asset' => $asset ]) }}">
        <article class="flex bg-white rounded shadow p-3 pl-5">
          <div class="flex-shrink-0 self-center">
            <img class="w-16 h-16 bg-gray-400" src="{{ $asset->icon_url }}">
          </div>
          <div class="ml-6 pt-1">
            <div class="title leading-relaxed">{{ $asset->title }}</div>
            <div class="author text-gray-600 text-sm">{{ $asset->author->name }}</div>
            <div class="text-sm -ml-px mt-2">
              <span class="m-1 px-3 py-1 bg-gray-200 rounded-full">{{ $asset->category }}</span>
              <span class="m-1 px-3 py-1 bg-gray-200 rounded-full">{{ $asset->godot_version }}</span>
              <span class="m-1 px-3 py-1 bg-gray-200 rounded-full">{{ $asset->support_level }}</span>
            </div>
          </div>
        </article>
      </a>
    </div>
    @endforeach
  </section>

  {{ $assets->appends(Request::all())->links() }}
</div>
@endsection
