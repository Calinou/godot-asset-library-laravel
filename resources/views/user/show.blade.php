@extends('layouts.app')

@section('title', __(":user's profile", ['user' => $user->username]))

@section('content')
<div class="container">
  <h1 class="text-center text-2xl font-medium">
    {{ __(":user's profile", ['user' => $user->username]) }}
  </h1>
  <h2 class="text-center text-gray-600 mt-2 mb-2">
    {{ __('Joined') }}
    @include('includes/date-relative', ['date' => \Carbon\Carbon::parse($user->created_at)])
    —
    @if ($user->assetReviews->count() >= 1)
    <a href="{{ route('user.reviews.index', ['user' => $user]) }}" class="link">
    @endif
    {{ trans_choice(
      '{0} Reviewed no assets|{1} Reviewed :count asset|[2,*] Reviewed :count assets',
      $user->assetReviews->count(),
      ['count' => $user->assetReviews->count()]
    ) }}
    @if ($user->assetReviews->count() >= 1)
    </a>
    @endif
  </h2>

  @can('block-user', $user)
  <div class="text-center">
    <h2 class="text-center text-xl font-medium mt-8 mb-4">
        {{ __('Actions') }}
      </h2>
    @if ($user->is_blocked)
    <form method="POST" action="{{ route('admin.unblock', ['user' => $user]) }}">
      @csrf
      @method('PUT')
      <button type="submit" class="button">
        <span class="fa fa-circle-o fa-fw -ml-1 mr-1 opacity-75"></span>
        {{ __('Unblock') }}
      </button>
    </form>
    @else
    <form method="POST" action="{{ route('admin.block', ['user' => $user]) }}">
      @csrf
      @method('PUT')
      <button type="submit" class="button text-red-700">
        <span class="fa fa-ban fa-fw -ml-1 mr-1 opacity-75"></span>
        {{ __('Block') }}
      </button>
    </form>
    @endif
  </div>
  @endcan

  <h2 class="text-center text-xl font-medium mt-16">
    {{ __('Assets by :user', ['user' => $user->username]) }}
  </h2>

  @if (count($user->assets) >= 1)
  <section class="flex flex-wrap -mx-2 mt-6">
    @foreach ($user->assets as $asset)
    @include('includes/asset-card', $asset)
    @endforeach
  </section>
  @else
  <div class="mt-8 text-lg text-center text-gray-600">
    {{ __(":user hasn't posted any assets yet.", ['user' => $user->username]) }}<br>
  </div>
  @endif
</div>
@endsection
