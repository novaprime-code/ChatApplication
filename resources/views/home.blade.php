@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ url('css/video.css') }}">
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
@endsection

@section('content')
    @if (auth()->user())
        <main-component :logged-in-user="{{ auth()->user() ? json_encode(auth()->user()) : 'null' }}"
                        pusher-key="{{ config('broadcasting.connections.pusher.key') }}"
                        pusher-cluster="{{ config('broadcasting.connections.pusher.options.cluster') }}">
        </main-component>
    @else
        <login-component></login-component>
    @endif
@endsection