@inject('assetClass', 'App\Asset')

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="apple-mobile-web-app-title" content="Godot Asset Library">
  <meta name="application-name" content="Godot Asset Library">
  <meta name="theme-color" content="#3d8fcc">
  <meta name="msapplication-TileColor" content="#ffffff">
  <title>@yield('title') - Godot Asset Library</title>
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/apple-touch-icon.png') }}">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/favicon-32x32.png') }}">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/favicon-16x16.png') }}">
  <link rel="manifest" href="{{ asset('/site.webmanifest') }}">
  <link rel="mask-icon" href="{{ asset('/safari-pinned-tab.svg') }}" color="#3d8fcc">
  <script defer src="{{ mix('js/manifest.js') }}"></script>
  <script defer src="{{ mix('js/vendor.js') }}"></script>
  <script defer src="{{ mix('js/app.js') }}"></script>
</head>
<body data-barba="wrapper">
  <header>
    <nav class="shadow bg-white p-2 mb-8">
      <div class="container flex flex-wrap justify-between">

        <div class="flex items-center">
          <a href="{{ route('asset.index') }}" class="navbar-link font-medium text-lg">
            Godot Asset Library
          </a>

          {{--
            Must be surrounded with `.relative` so that the absolute-positioned
            icon visually stays in the input field
          --}}
          <div class="relative">
            <form method="GET" action="{{ route('asset.index') }}" class="ml-2">
              <input
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
          <a href="{{ route('asset.create') }}" class="navbar-link">
            {{ __('Submit asset') }}
          </a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="navbar-link" type="submit">
              {{ __('Log out') }} ({{ Auth::user()->name }})
            </button>
          </form>
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
    {{-- Display flash message --}}
    @if (session('status'))
    <div class="container">
      <div class="bg-white p-3 rounded shadow mb-4">
        {{ session('status') }}
      </div>
    </div>
    @endif

    {{-- Display email verification success --}}
    @if (session('verified'))
    <div class="container">
      <div class="bg-white p-3 rounded shadow mb-4">
        {{ __("You've successfully verified your email address!") }}
      </div>
    </div>
    @endif

    @yield('content')

    <footer class="mt-12 py-12 bg-gray-300 text-gray-600 text-center">
      © 2019 Godot Asset Library
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
