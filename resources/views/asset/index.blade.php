@extends('layouts.app')

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

{{-- Remove container padding for better display on mobile devices --}}
<div class="container px-0">
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

  <div class="relative text-right mr-2">
    {{--
      Avoid overlapping the list of assets when browsing a specific category
      by adding a negative margin
    --}}
    <form method="GET" action="{{ route('asset.index') }}" id="sort-form" class="md:absolute md:right-0 md:-mt-10">
      @component('components/form-select', [
        'name' => 'sort',
        {{-- Preserve the form value across reloads --}}
        'value' => Request::get('sort'),
        'label' => __('Sort by'),
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
    @include('includes/asset-card', $asset)
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
