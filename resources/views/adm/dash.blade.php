<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <span class="font-bold text-xl">Admin Panel</span>
        <form action="{{ route('auth.logout') }}" method="POST">
            @csrf
            <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
        </form>
    </nav>

    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Wiki Pages</h1>
            <a href="{{ route('admin.create') }}" class="bg-green-500 text-white px-4 py-2 rounded">Create New</a>
        </div>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow rounded overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Title</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Slug</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $page)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $page->title }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $page->slug }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <a href="{{ route('admin.edit', $page->id) }}" class="text-blue-600 hover:text-blue-900 mr-4">Edit</a>
                            <form action="{{ route('admin.destroy', $page->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-4">
                {{ $pages->links() }}
            </div>
        </div>
    </div>
</body>
</html>