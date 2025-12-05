@extends('layout.AdmLayout')

@section('content')
<div class="flex flex-col space-y-6 pb-12" x-data="{ tab: 'active' }">
    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <a href="{{ route('manpower.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#002d5b] uppercase tracking-wide">&larr; Back to List</a>
            <h2 class="text-3xl font-extrabold text-slate-800 mt-1">Monthly Recap</h2>
        </div>
        
        <div class="flex items-center gap-2">
            <form method="GET" action="{{ route('manpower.recap') }}" class="flex items-center gap-2 bg-white p-1 rounded shadow-sm border border-slate-200">
                <input type="month" name="month" value="{{ $month }}" class="text-sm border-0 focus:ring-0 text-slate-600 font-bold" onchange="this.form.submit()">
            </form>
            <div class="h-8 w-px bg-slate-300 mx-2"></div>
            <a href="{{ route('manpower.import', ['month' => $month]) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded text-xs font-bold shadow-sm transition">Import {{ \Carbon\Carbon::parse($month)->format('M Y') }}</a>
            <a href="{{ route('manpower.export', ['month' => $month]) }}" class="bg-slate-700 hover:bg-slate-800 text-white px-3 py-2 rounded text-xs font-bold shadow-sm transition">Export PDF/Excel</a>
        </div>
    </div>

    {{-- Overview Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-blue-600">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Active MP (Unique)</p>
            <h3 class="text-3xl font-black text-slate-800">{{ number_format($totals['active_mp']) }}</h3>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-yellow-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Accumulated Hours</p>
            <h3 class="text-3xl font-black text-slate-800">{{ number_format($totals['total_mh'], 1) }}</h3>
        </div>
        <div class="bg-white p-5 rounded-lg shadow-sm border-l-4 border-red-400">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Inactive / Out</p>
            <h3 class="text-3xl font-black text-slate-800">{{ number_format($totals['out_mp']) }}</h3>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex gap-4 border-b border-slate-200">
        <button @click="tab = 'active'" :class="{'border-blue-600 text-blue-700': tab === 'active', 'border-transparent text-slate-500 hover:text-slate-700': tab !== 'active'}" class="pb-3 border-b-2 font-bold text-sm transition-colors uppercase tracking-wide">
            Active Manpower Log
        </button>
        <button @click="tab = 'inactive'" :class="{'border-red-600 text-red-700': tab === 'inactive', 'border-transparent text-slate-500 hover:text-slate-700': tab !== 'inactive'}" class="pb-3 border-b-2 font-bold text-sm transition-colors uppercase tracking-wide">
            Separation / Out Log
        </button>
    </div>

    {{-- Active Table --}}
    <div x-show="tab === 'active'" class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden flex flex-col">
        <div class="overflow-auto scrollbar-thin scrollbar-thumb-slate-300">
            <table class="text-sm text-left border-collapse w-full whitespace-nowrap">
                <thead class="bg-slate-50 text-slate-600 font-bold uppercase text-xs">
                    <tr>
                        <th class="p-3 border-b border-slate-200 w-12 text-center">#</th>
                        <th class="p-3 border-b border-slate-200">Name / NRP</th>
                        <th class="p-3 border-b border-slate-200">Role</th>
                        <th class="p-3 border-b border-slate-200">Company</th>
                        <th class="p-3 border-b border-slate-200 text-right bg-blue-50 text-blue-800">Acc. Hours</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($activeMatrix as $index => $row)
                        <tr class="hover:bg-blue-50/20 transition-colors">
                            <td class="p-3 text-center text-slate-400 font-mono text-xs">{{ $loop->iteration }}</td>
                            <td class="p-3">
                                <div class="font-bold text-slate-800">{{ $row['name'] }}</div>
                                <div class="text-xs text-slate-500 font-mono">{{ $row['nrp'] ?? '-' }}</div>
                            </td>
                            <td class="p-3 text-xs text-slate-600 font-medium">{{ $row['role'] }}</td>
                            <td class="p-3 text-xs text-slate-500">{{ $row['company'] }}</td>
                            <td class="p-3 text-right font-mono font-bold text-blue-700 bg-blue-50/30">
                                {{ number_format($row['total_mh'], 1) }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-slate-400">No active data logs found for this month.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Inactive Table --}}
    <div x-show="tab === 'inactive'" x-cloak class="bg-white rounded-lg shadow-sm border border-red-100 overflow-hidden flex flex-col">
        <div class="overflow-auto scrollbar-thin scrollbar-thumb-slate-300">
            <table class="text-sm text-left border-collapse w-full whitespace-nowrap">
                <thead class="bg-red-50 text-red-800 font-bold uppercase text-xs">
                    <tr>
                        <th class="p-3 border-b border-red-200 w-12 text-center">#</th>
                        <th class="p-3 border-b border-red-200">Name / NRP</th>
                        <th class="p-3 border-b border-red-200">Company</th>
                        <th class="p-3 border-b border-red-200">Date Out</th>
                        <th class="p-3 border-b border-red-200">Reason</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-50">
                    @forelse($inactiveData as $row)
                        <tr class="hover:bg-red-50/50 transition-colors">
                            <td class="p-3 text-center text-slate-400 font-mono text-xs">{{ $loop->iteration }}</td>
                            <td class="p-3">
                                <div class="font-bold text-slate-800">{{ $row->name }}</div>
                                <div class="text-xs text-slate-500 font-mono">{{ $row->nrp ?? '-' }}</div>
                            </td>
                            <td class="p-3 text-xs text-slate-500">{{ $row->company }}</td>
                            <td class="p-3 text-xs font-bold text-red-600">
                                {{ $row->date_out ? \Carbon\Carbon::parse($row->date_out)->format('d M Y') : '-' }}
                            </td>
                            <td class="p-3 text-xs font-medium text-slate-700 bg-red-50/30">
                                {{ $row->out_reason }}
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="p-8 text-center text-slate-400">No separation records found for this month.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="//unpkg.com/alpinejs" defer></script>
@endsection