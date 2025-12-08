@extends('layout.AdmLayout')
@section('content')
<div class="flex flex-col space-y-6 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div>
            <div class="flex items-center gap-3">
                <a href="{{ route('manpower.index') }}" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg></a>
                <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Daily Recap: {{ $log->log_date->format('d M Y') }}</h2>
            </div>
            <p class="text-sm text-slate-500 font-medium mt-1 ml-9">Snapshot recorded at {{ $log->updated_at->format('H:i') }}</p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('manpower.log.edit', $log->id) }}" class="bg-indigo-100 text-indigo-700 px-4 py-2 rounded-lg font-bold text-sm hover:bg-indigo-200 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit / Correct
            </a>
            <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm"><span class="text-xs text-slate-400 font-bold uppercase block">Total MP</span><span class="text-xl font-black text-slate-800">{{ $log->total_mp }}</span></div>
            <div class="bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm"><span class="text-xs text-slate-400 font-bold uppercase block">Total Daily Hours</span><span class="text-xl font-black text-blue-600">{{ number_format($log->total_mh, 2) }}</span></div>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm font-medium">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-auto scrollbar-thin scrollbar-thumb-slate-300">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-bold">
                    <tr>
                        <th class="px-6 py-4 w-16">No</th>
                        <th class="px-6 py-4">Name / NRP</th>
                        <th class="px-6 py-4">Company / Site</th>
                        <th class="px-6 py-4">Role / Dept</th>
                        <th class="px-6 py-4 text-right text-slate-400">Prev. MH</th>
                        <th class="px-6 py-4 text-right text-slate-400">Cumulative</th>
                        <th class="px-6 py-4 text-right text-blue-700 bg-blue-50/50">Daily Delta</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($log->content as $index => $row)
                    @php 
                        $row = (object) $row;
                        $daily = $row->manhours ?? 0;
                        $cumul = $row->cumulative_mh ?? 0;
                        $prev = $row->previous_mh ?? 0;
                    @endphp
                    <tr class="hover:bg-slate-50/50">
                        <td class="px-6 py-3 text-center text-slate-400 font-mono text-xs">{{ $loop->iteration }}</td>
                        <td class="px-6 py-3"><div class="font-bold text-slate-800">{{ $row->name }}</div><div class="text-xs text-slate-500 font-mono">{{ $row->nrp ?? '-' }}</div></td>
                        <td class="px-6 py-3"><div class="font-semibold text-slate-700">{{ Str::limit($row->company, 20) }}</div><div class="text-xs text-slate-500">{{ $row->site }}</div></td>
                        <td class="px-6 py-3"><div class="text-slate-700 font-medium">{{ $row->role ?? '-' }}</div><div class="text-[10px] uppercase text-slate-400 font-bold tracking-wide">{{ $row->department ?? '' }}</div></td>
                        
                        <td class="px-6 py-3 text-right font-mono text-xs text-slate-400">{{ number_format($prev, 2) }}</td>
                        <td class="px-6 py-3 text-right font-mono text-xs text-slate-500">{{ number_format($cumul, 2) }}</td>
                        <td class="px-6 py-3 text-right font-mono font-bold text-blue-700 bg-blue-50/30">{{ number_format($daily, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection