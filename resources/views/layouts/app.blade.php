<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="theme-color" content="#3d8fcc">
  <title>@yield('title') - Godot Asset Library</title>
  <link rel="stylesheet" href="{{ mix('css/app.css') }}">
  <script defer src="{{ mix('js/manifest.js') }}"></script>
  <script defer src="{{ mix('js/vendor.js') }}"></script>
  <script defer src="{{ mix('js/app.js') }}"></script>
</head>
<body data-barba="wrapper">
  <header>
    <nav class="shadow bg-white p-2">
      <div class="container flex flex-wrap justify-between">

        <div class="flex items-center mr-6">
          <a href="{{ route('asset.index') }}" class="font-medium text-lg">Godot Asset Library</a>
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
          {{ Auth::user()->name }}
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">{{ __('Log out') }}</button>
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
    @yield('content')
  </main>
</body>
</html>
