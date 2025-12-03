<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="w-full max-w-xs">
        <form action="{{ route('auth.signin') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                <input name="username" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" autofocus>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <input name="password" type="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline">
                @error('username') <p class="text-red-500 text-xs italic">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Sign In
                </button>
            </div>
        </form>
        <p class="text-center text-gray-500 text-xs">&copy;2025 WikiSystem. All rights reserved.</p>
    </div>
</body>
</html>