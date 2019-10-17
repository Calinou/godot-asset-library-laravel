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
          {{--
            Offset the right panel slightly on the Y axis to make tags
            appear slightly further from the bottom, which looks better
          --}}
          <div class="ml-6 pt-1 -mt-px mb-px">
            <div class="leading-relaxed font-medium">{{ $asset->title }}</div>
            <div class="text-gray-600 text-sm my-px">
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

  {{ $assets->appends(Request::all())->links() }}
</div>
@endsection
