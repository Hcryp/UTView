@extends('layout.base')
@section('title', '| Public Wiki')
@section('sidebar')
<aside class="w-64 bg-white border-r border-gray-200 h-screen p-4 hidden md:block">
    <h1 class="font-bold text-xl text-yellow-500 mb-6">UTView Wiki</h1>
    <nav class="space-y-2">
        @foreach($topics as $topic)
        <a href="#" class="block px-4 py-2 rounded hover:bg-yellow-50 text-sm font-medium">{{ $topic }}</a>
        @endforeach
    </nav>
</aside>
@endsection
@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-3xl font-bold mb-4">Welcome to United Tractors Wiki</h2>
    <p class="text-gray-600">Select a topic from the sidebar to view operational guidelines.</p>
</div>
@endsection