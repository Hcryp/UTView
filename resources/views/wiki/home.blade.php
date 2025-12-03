<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Wiki</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white text-gray-800">
    <header class="border-b p-6">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-3xl font-bold"><a href="{{ route('wiki.home') }}">Wiki</a></h1>
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-800">Admin Login</a>
        </div>
    </header>

    <main class="container mx-auto p-6">
        <div class="grid gap-6">
            @forelse($pages as $page)
                <div class="border rounded p-4 hover:shadow-lg transition">
                    <h2 class="text-xl font-bold mb-2">
                        <a href="{{ route('wiki.show', $page->slug) }}">{{ $page->title }}</a>
                    </h2>
                    <p class="text-gray-600">{{ Str::limit(strip_tags($page->content), 150) }}</p>
                </div>
            @empty
                <p class="text-gray-500">No wiki pages available.</p>
            @endforelse
        </div>
        
        <div class="mt-6">
            {{ $pages->links() }}
        </div>
    </main>
</body>
</html>