<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTView @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-slate-800 font-sans antialiased">
    <div class="min-h-screen flex">
        @yield('sidebar')
        <main class="flex-1 p-6">@yield('content')</main>
    </div>
</body>
</html>