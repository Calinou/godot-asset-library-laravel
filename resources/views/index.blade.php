@extends('layouts.app')

@section('title', 'Home')

@section('content')
<h2>Welcome</h2>
@if (session('status'))
  {{ session('status') }}
@endif
<a href="{{ route('register') }}">Sign up</a>
<a href="{{ route('login') }}">Log in</a>
<form method="POST" action="{{ route('logout') }}">
  @csrf
  <button type="submit">Log out</a>
</form>
<hr>
<a href="{{ route('asset.show', ['id' => 1]) }}">Snake</a>
<a href="{{ route('asset.show', ['id' => 2]) }}">Tetris</a>
<a href="{{ route('asset.show', ['id' => 3]) }}">Pong</a>
@endsection
