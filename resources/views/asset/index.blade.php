@extends('layouts.app')
@inject('assetClass', 'App\Asset')

@if (Request::get('filter'))
@section('title', __('Search - :filter', ['filter' => Request::get('filter')]))
@else
@section('title', __('Home'))
@endif

@php
$description = __('Find add-ons, assets and scripts for your projects here.');
@endphp
@section('description', $description)

@section('content')
@if (!Request::get('filter') && $assets->currentPage() === 1)
<div class="bg-indigo-700 text-white -mt-8 mb-8 py-10">
  <div class="container">
    <h1 class="text-3xl font-medium mb-6">
      {{ __('Welcome to the :appName', ['appName' => config('app.name')]) }}
    </h1>
    <p class="text-lg">
      {{ $description }}
    </p>
  </div>
</div>
@endif

<div class="container">
  <h2 class="text-center text-2xl font-medium">
    @if (Request::get('filter'))
    {{ trans_choice(
      '{0} No results for “:filter”|{1} :count result for “:filter”|[2,*] :count results for “:filter”',
      $assets->total(),
      ['filter' => Request::get('filter')]
    ) }}
    @else

    @switch (Request::get('sort'))
    @case ('name')
    {{ __('Assets by name') }}
    @break
    @case ('rating')
    {{ __('Top-scoring assets') }}
    @break
    @case ('cost')
    {{ __('Assets by license') }}
    @break
    @default
    {{-- Also handles `updated` --}}
    {{ __('Recent assets') }}
    @break
    @endswitch

    @endif
  </h2>

  <div class="relative text-right">
    {{--
      Avoid overlapping the list of assets when browsing a specific category
      by adding a negative margin
    --}}
    <form method="GET" action="{{ route('asset.index') }}" id="sort-form" class="md:absolute md:right-0 md:-mt-10">
      @component('components/form-select', [
        'name' => 'sort',
        'value' => old('sort'),
        'label' => __('Sort by'),
        'placeholder' => __('.'),
        'required' => false,
        'choices' => [
          'updated' => 'Updated',
          'name' => 'Name',
          'rating' => 'Score',
          'cost' => 'License',
        ],
      ])
      @endcomponent
    </form>
  </div>

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
        <article class="flex bg-white rounded shadow hover-active-darken">
          <div class="flex-shrink-0 self-center">
            <img class="object-cover w-26 h-26 bg-gray-400 rounded-l" src="{{ $asset->icon_url }}">
          </div>
          {{--
            Offset the right panel slightly on the Y axis to make tags
            appear slightly further from the bottom, which looks better
          --}}
          <div class="ml-6 py-3 pl-1 -mt-px mb-px w-full pr-3">
            <div class="flex space-between">
              <div class="leading-relaxed font-medium">{{ $asset->title }}</div>
              <div class="flex-grow text-right text-sm {{ $asset->score_color }}">
                <span class="fa mr-1 opacity-50 @if ($asset->score >= 0) fa-thumbs-up @else fa-thumbs-down @endif"></span>
                {{ $asset->score }}
              </div>
            </div>
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
