@extends('layouts.app')

@section('title', 'Home')

@section('content')
<h2>Welcome</h2>
<a href="{{ route('asset.show', ['id' => 1]) }}">Snake</a>
<a href="{{ route('asset.show', ['id' => 2]) }}">Tetris</a>
<a href="{{ route('asset.show', ['id' => 3]) }}">Pong</a>
@endsection
