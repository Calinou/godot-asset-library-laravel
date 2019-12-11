@inject('assetClass', 'App\Asset')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="apple-mobile-web-app-title" content="{{ config('app.name') }}">
  <meta name="application-name" content="{{ config('app.name') }}">
  <meta name="theme-color" content="#3d8fcc">
  <meta name="msapplication-TileColor" content="#ffffff">
  <meta name="description" content="@yield('description')">

  <title>@yield('title') - {{ config('app.name') }}</title>

  <meta property="og:title" content="@yield('title') - {{ config('app.name') }}">
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url()->current() }}">
  <meta property="og:image" content="@yield('image')">
  <meta property="og:description" content="@yield('description')">
  <meta property="og:site_name" content="{{ config('app.name') }}">

  {{--
    Wrap `mix()` calls in `asset()` to keep working paths when hosting the app in a subdirectory.
    <https://github.com/JeffreyWay/laravel-mix/issues/1026>
  --}}
  <link rel="stylesheet" href="{{ asset(mix('css/app.css')) }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('/site.webmanifest') }}">
  <link rel="mask-icon" href="{{ asset('/safari-pinned-tab.svg') }}" color="#3d8fcc">
  <script defer src="{{ asset(mix('js/manifest.js')) }}"></script>
  <script defer src="{{ asset(mix('js/vendor.js')) }}"></script>
  <script defer src="{{ asset(mix('js/app.js')) }}"></script>
</head>
<body data-barba="wrapper">
  <header>
    <nav class="shadow bg-white p-2 mb-8">
      <div class="container flex flex-wrap justify-between">

        <div class="flex items-center">
          <a href="{{ route('asset.index') }}" class="navbar-link font-medium text-lg">
            {{ config('app.name') }}
          </a>

          {{--
            Must be surrounded with `.relative` so that the absolute-positioned
            icon visually stays in the input field
          --}}
          <div class="relative">
            @php
            $searchTooltip = __(<<<EOF
Press / to focus this field.
This will search in the asset's title, blurb and tags.
This field supports search string syntax. Examples:

Hello world  —  Search for "Hello" and "world" individually
"Hello world"  —  Perform an exact match instead of matching words individually
score >= 3  —  Show assets with a score greater than or equal to 3
license = MIT  —  Show assets licensed under the MIT license (use SPDX identifiers)
updated_at > 2019-01-01  —  Show assets updated after January 1 2019
EOF);
            @endphp
            <form method="GET" action="{{ route('asset.index') }}" class="ml-2"
              aria-label="{{ $searchTooltip }}"
              data-balloon-pos="down"
              data-balloon-break
            >
              <input
                id="asset-search"
                name="filter"
                placeholder="{{ __('Search for assets') }}"
                value="{{ Request::get('filter') }}"
                class="form-input-text shadow-none bg-gray-200 lg:w-64"
              >
              <span class="fa fa-search absolute right-0 mt-2 mr-3 pointer-events-none text-gray-500"></span>
            </form>
          </div>

          <div class="navbar-dropdown">
            <a href="{{ route('asset.index') }}" class="button ml-2">
              Categories <span class="fa fa-angle-down ml-1"></span>
            </a>
            <div class="navbar-dropdown-content">
              @foreach (range(0, $assetClass::CATEGORY_MAX - 1) as $categoryId)

              <a href="{{ route('asset.index', ['category' => $categoryId]) }}" class="block button rounded-none px-6">
                <span class="fa {{ $assetClass::getCategoryIcon($categoryId) }} fa-fw mr-1 -ml-2 opacity-75"></span>
                {{ $assetClass::getCategoryName($categoryId) }}
              </a>
              @endforeach
            </div>
          </div>
        </div>

        <div class="block lg:hidden">
          <button class="flex items-center px-3 py-2">
            <svg class="fill-current h-4 w-4" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
              <title>Menu</title>
              <path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"/>
            </svg>
          </button>
        </div>

        <div class="w-full lg:flex lg:items-center lg:w-auto">
          @if (Auth::check())
          @can('submit-asset')
          <a href="{{ route('asset.create') }}" class="navbar-link">
            {{ __('Submit asset') }}
          </a>
          @endcan
          <div class="navbar-dropdown">
            <a href="{{ route('user.show', ['user' => Auth::user()]) }}" class="button">
              {{ Auth::user()->name }} <span class="fa fa-angle-down ml-1"></span>
            </a>
            <div class="navbar-dropdown-content">
              <a href="{{ route('profile.edit') }}" class="block button rounded-none px-6">
                <span class="fa fa-cogs fa-fw mr-1 -ml-2 opacity-75"></span>
                {{ __('Settings') }}
                </a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="block button rounded-none px-6" type="submit" data-loading>
                  <span class="fa fa-sign-out fa-fw mr-1 -ml-2 opacity-75"></span>
                  {{ __('Log out') }}
                </button>
              </form>
            </div>
          </div>
          @else
          <a href="{{ route('register') }}" class="navbar-link">
            {{ __('Sign up') }}
          </a>
          <a href="{{ route('login') }}" class="navbar-link">
            {{ __('Log in') }}
          </a>
          @endif
        </div>

      </div>
    </nav>
  </header>

  <main data-barba="container">
    @if (session('status'))
    {{-- Display a dismissable flash message --}}
    <div class="container relative">
      @component('components/alert', [
        'type' => session('statusType', 'info'),
      ])
      {{ session('status') }}
      @endcomponent

      <button type="button" data-flash-close class="absolute top-0 right-0 px-4 py-3 mr-3">
        <span class="fa fa-close fa-fw opacity-50"></span>
      </button>
    </div>
    @endif

    {{-- Display email verification success --}}
    @if (session('verified'))
    <div class="container">
      @component('components/alert', [
        'type' => 'success',
      ])
      {{ __("You've successfully verified your email address!") }}
      @endcomponent
    </div>
    @endif

    @yield('content')

    <footer class="mt-12 py-12 bg-gray-300 text-gray-600 text-center">
      © 2019 {{ config('app.name') }}
      @can('admin')
        —
        <a class="link text-center" href="{{ route('admin.index') }}">
          {{ __('Administration panel') }}
        </a>
      @endcan
    </footer>
  </main>
</body>
</html>
