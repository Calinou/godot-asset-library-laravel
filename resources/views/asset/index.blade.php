@extends('layouts.app')
@inject('assetClass', 'App\Asset')

@if (Request::get('filter'))
@section('title', __('Search - :filter', ['filter' => Request::get('filter')]))
@else
@section('title', __('Home'))
@endif

@section('content')
<div class="container">
  <h2 class="text-center text-xl font-medium">
    @if (Request::get('filter'))

    @if ($assets->count() == 0)
    {{ __('No results for “:filter”', ['filter' => Request::get('filter')]) }}
    @elseif ($assets->count() == 1)
    {{ __('1 result for “:filter”', ['filter' => Request::get('filter')]) }}
    @else
    {{ __(':count results for “:filter”', ['count' => $assets->count(), 'filter' => Request::get('filter')]) }}
    @endif

    @else
    {{ __('Welcome to the Godot Asset Library') }}
    @endif
  </h2>

  {{--
    We must append the query parameters so that search filters carry on
    when the user clicks a page number
  --}}
  {{ $assets->appends(Request::all())->links() }}

  @if ($assets->items())
  <section class="flex flex-wrap -mx-2 mt-8">
    @foreach ($assets->items() as $asset)
    <div class="w-full lg:w-1/2 px-2 my-2">
      <a href="{{ route('asset.show', ['asset' => $asset ]) }}">
        <article class="flex bg-white rounded shadow">
          <div class="flex-shrink-0 self-center">
            <img class="object-cover w-26 h-26 bg-gray-400 rounded-l" src="{{ $asset->icon_url }}">
          </div>
          {{--
            Offset the right panel slightly on the Y axis to make tags
            appear slightly further from the bottom, which looks better
          --}}
          <div class="ml-6 py-3 pl-1 -mt-px mb-px">
            <div class="leading-relaxed font-medium">{{ $asset->title }}</div>
            <div class="leading-relaxed text-gray-600 text-sm my-px">
              @if ($asset->blurb)
              {{ $asset->blurb }}
              @else
              {{ __('by :author', ['author' => $asset->author->name]) }}
              @endif
            </div>
            <div class="text-sm -ml-px mt-2">
              <span class="m-1 px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                <span class="fa {{ $asset->category_icon }} fa-fw mr-1 -ml-1 opacity-75"></span>
                {{ $asset->category }}
              </span>
              <span class="m-1 px-3 py-1 bg-gray-200 rounded-full">{{ $asset->godot_version }}</span>
              @php
                switch (intval($asset->support_level_id)) {
                  case ($assetClass::SUPPORT_LEVEL_OFFICIAL):
                    $supportLevelClasses = 'bg-green-100 text-green-800';
                    $supportLevelIcon = 'fa-check';
                    break;
                  case ($assetClass::SUPPORT_LEVEL_COMMUNITY):
                    $supportLevelClasses = 'bg-gray-200';
                    $supportLevelIcon = '';
                    break;
                  case ($assetClass::SUPPORT_LEVEL_TESTING):
                    $supportLevelClasses = 'bg-yellow-200 text-yellow-800';
                    $supportLevelIcon = 'fa-exclamation-circle';
                    break;
                  default:
                    throw new \Exception("Invalid support level: $asset->support_level_id");
                    break;
                }
              @endphp
              <span class="m-1 px-3 py-1 rounded-full {{ $supportLevelClasses }}">
                @if ($supportLevelIcon)
                  <span class="fa {{ $supportLevelIcon }} fa-fw mr-1 -ml-1 opacity-75"></span>
                @endif
                {{ $asset->support_level }}
              </span>
            </div>
          </div>
        </article>
      </a>
    </div>
    @endforeach
  </section>
  @else
  <div class="mt-12 text-lg text-center text-gray-600 leading-loose">
    {{ __('No assets found.') }}<br>
    <a class="link" href="{{ route('asset.index') }}">
      {{ __('View all assets') }}
    </a>
  </div>
  @endif

  {{ $assets->appends(Request::all())->links() }}
</div>
@endsection
