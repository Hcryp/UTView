<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UT Satui Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .ut-yellow { background-color: #FCCF00; color: black; }
        .ut-header { border-bottom: 4px solid #000; }
    </style>
</head>
<body class="bg-gray-100 font-sans text-sm">

    <header class="ut-yellow ut-header p-4 flex justify-between items-center shadow-md">
        <div class="flex items-center gap-4">
            <div class="bg-black text-white font-bold p-2 text-lg tracking-wider">UT</div>
            <div>
                <h1 class="font-bold text-xl uppercase">Dashboard Operational</h1>
                <p class="text-xs font-semibold opacity-80">Site: SATUI | Period: Oct 2025</p>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <span class="font-bold uppercase">{{ $user->username }}</span>
            <form action="{{ route('adm.out') }}" method="POST">
                @csrf
                <button class="bg-black text-white px-4 py-1 font-bold hover:bg-gray-800 transition">LOGOUT</button>
            </form>
        </div>
    </header>

    <main class="p-6 max-w-7xl mx-auto space-y-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white border-l-8 border-yellow-500 p-4 shadow">
                <p class="text-gray-500 font-bold text-xs uppercase">Total Manpower</p>
                <p class="text-4xl font-black text-gray-800">{{ number_format($stats['mp']) }}</p>
                <p class="text-xs text-green-600 font-bold mt-1">Active Personnel</p>
            </div>
            <div class="bg-white border-l-8 border-black p-4 shadow">
                <p class="text-gray-500 font-bold text-xs uppercase">Total Manhours</p>
                <p class="text-4xl font-black text-gray-800">{{ number_format($stats['mh']) }}</p>
                <p class="text-xs text-gray-600 font-bold mt-1">Safe Work Hours</p>
            </div>
            <div class="bg-white border-l-8 border-yellow-500 p-4 shadow">
                <p class="text-gray-500 font-bold text-xs uppercase">Companies/Partners</p>
                <p class="text-4xl font-black text-gray-800">{{ $stats['companies'] }}</p>
                <p class="text-xs text-gray-600 font-bold mt-1">Contractors & Internal</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <div class="lg:col-span-1 bg-white shadow rounded overflow-hidden">
                <div class="ut-yellow px-4 py-3 border-b-2 border-black">
                    <h3 class="font-bold uppercase">Summary by Company</h3>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-200 text-xs uppercase">
                        <tr>
                            <th class="p-2 border-b border-gray-300">Company</th>
                            <th class="p-2 border-b border-gray-300 text-right">MP</th>
                            <th class="p-2 border-b border-gray-300 text-right">Hours</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs">
                        @foreach($summary as $row)
                        <tr class="odd:bg-white even:bg-gray-50 hover:bg-yellow-50">
                            <td class="p-2 border-b border-gray-100 font-semibold">{{ Str::limit($row->company, 20) }}</td>
                            <td class="p-2 border-b border-gray-100 text-right">{{ $row->manpower }}</td>
                            <td class="p-2 border-b border-gray-100 text-right">{{ number_format($row->total_hours) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="lg:col-span-2 bg-white shadow rounded overflow-hidden">
                <div class="ut-yellow px-4 py-3 border-b-2 border-black flex justify-between items-center">
                    <h3 class="font-bold uppercase">Personnel Detail (Last 50 Entries)</h3>
                    <button class="text-xs bg-black text-white px-2 py-1 hover:bg-gray-800">Export CSV</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-800 text-white text-xs uppercase">
                            <tr>
                                <th class="p-3">NRP</th>
                                <th class="p-3">Name</th>
                                <th class="p-3">Position</th>
                                <th class="p-3">Company</th>
                                <th class="p-3 text-right">Days</th>
                                <th class="p-3 text-right">Hours</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs text-gray-700">
                            @foreach($details as $d)
                            <tr class="border-b border-gray-100 hover:bg-yellow-100 transition">
                                <td class="p-3 font-mono">{{ $d->nrp ?? '-' }}</td>
                                <td class="p-3 font-bold">{{ $d->name }}</td>
                                <td class="p-3">{{ $d->position }}</td>
                                <td class="p-3 text-[10px] uppercase text-gray-500">{{ $d->company }}</td>
                                <td class="p-3 text-right">{{ $d->work_days }}</td>
                                <td class="p-3 text-right font-semibold">{{ number_format($d->manhours) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</body>
</html>