@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div class="container">
  <h2>Welcome</h2>

  @if (session('status'))
    {{ session('status') }}
  @endif

  <a href="{{ route('register') }}">Sign up</a>
  <a href="{{ route('login') }}">Log in</a>

  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Log out</button>
  </form>

  <hr>

  <section class="flex flex-wrap -mx-2">
    @foreach ($assets as $asset)
      <div class="w-full lg:w-1/2 px-2 my-2">
        <a href="{{ route('asset.show', ['id' => $asset->id ]) }}">
          <article class="flex bg-white rounded shadow p-3">
            <div class="flex-shrink-0 self-center">
              <img class="w-16 h-16">
            </div>
            <div class="ml-6 pt-1">
              <div class="title leading-relaxed">{{ $asset->title }}</div>
              <div class="author text-gray-600 text-sm">{{ $asset->author }}</div>
              <div class="text-sm -ml-px mt-2">
                <span class="m-1 px-3 py-1 bg-gray-200 rounded-full">{{ $asset::getCategoryName($asset->category) }}</span>
                <span class="m-1 px-3 py-1 bg-gray-200 rounded-full">{{ $asset->godot_version }}</span>
                <span class="m-1 px-3 py-1 bg-gray-200 rounded-full">{{ $asset::getSupportLevelName($asset->support_level) }}</span>
              </div>
            </div>
          </article>
        </a>
      </div>
    @endforeach
  </section>
</div>
@endsection
