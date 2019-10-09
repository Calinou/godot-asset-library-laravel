@extends('layouts.app')
@inject('assetClass', 'App\Asset')

@section('title', 'Home')

@section('content')
<div class="container">
  <h2>Welcome to the Godot Asset Library</h2>

  @if (session('status'))
  {{ session('status') }}
  @endif

  <section>
    <form method="GET" action="{{ route('asset.index') }}">
      <label for="category">{{ __('Category') }}</label>
      <select id="category" name="category">
        <option value="" @if (Request::get('category') === null) selected @endif>Any</option>

        @foreach (range(0, $assetClass::CATEGORY_MAX - 1) as $categoryId)
        <option value="{{ $categoryId }}" @if (intval(Request::get('category')) === $categoryId) selected @endif>
          {{ $assetClass::getCategoryName($categoryId) }}
        </option>
        @endforeach
      </select>

      <label for="reverse">{{ __('Reverse') }}</label>
      <input type="checkbox" id="reverse" name="reverse" @if (Request::get('reverse')) checked @endif>

      <input name="filter" placeholder="Search assets" value="{{ Request::get('filter') }}">
      <button>{{ __('Search') }}</button>
    </form>
  </section>

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
