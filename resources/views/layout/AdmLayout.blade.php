<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTView Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .ut-blue { background-color: #002d5b; } /* UT Corporate Blue */
        .ut-yellow { background-color: #facc15; } /* UT Safety Yellow */
    </style>
</head>
<body class="bg-gray-100 font-sans text-sm text-slate-800">
    
    @include('layout.AdmTop')

    <div class="flex h-[calc(100vh-64px)] overflow-hidden">
        @include('layout.AdmSide')

        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

</body>
</html>