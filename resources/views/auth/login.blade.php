@extends('layout.base')
@section('title', '| Admin Login')
@section('content')
<div class="flex items-center justify-center h-[80vh]">
    <div class="w-full max-w-xs">
        <form action="{{ route('auth.check') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4 border border-gray-100">
            @csrf
            <h2 class="text-xl font-bold mb-6 text-center text-slate-700">UTView Admin</h2>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-yellow-400" id="username" type="text" name="username" required autofocus>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:ring-2 focus:ring-yellow-400" id="password" type="password" name="password" required>
                @error('username') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition" type="submit">
                    Sign In
                </button>
            </div>
        </form>
        <p class="text-center text-gray-500 text-xs">&copy; PT United Tractors. Internal Use Only.</p>
    </div>
</div>
@endsection