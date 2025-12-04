@extends('layout.AdmLayout')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-2 text-sm text-slate-500">
            <a href="{{ route('manpower.index') }}" class="hover:text-[#002d5b]">Manpower</a>
            <span>/</span>
            <span class="font-bold text-slate-700">{{ $mp->name }}</span>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('manpower.edit', $mp->id) }}" class="px-4 py-2 bg-yellow-400 hover:bg-yellow-500 text-black font-bold rounded shadow text-sm transition">Edit Profile</a>
            <form action="{{ route('manpower.destroy', $mp->id) }}" method="POST" onsubmit="return confirm('Delete this record permanently?');">
                @csrf @method('DELETE')
                <button class="px-4 py-2 bg-white border border-red-200 text-red-600 hover:bg-red-50 font-bold rounded shadow-sm text-sm transition">Delete</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative">
                <div class="h-24 bg-[#002d5b]"></div>
                <div class="px-6 relative">
                    <div class="w-20 h-20 bg-slate-200 rounded-full border-4 border-white absolute -top-10 flex items-center justify-center text-2xl font-bold text-slate-400">
                        {{ substr($mp->name, 0, 1) }}
                    </div>
                </div>
                <div class="mt-12 px-6 pb-6">
                    <h1 class="text-xl font-bold text-slate-800">{{ $mp->name }}</h1>
                    <p class="text-sm text-slate-500 mb-4">{{ $mp->role ?? 'No Job Title' }}</p>
                    
                    <div class="flex flex-col gap-2 mb-6">
                        <span class="inline-flex items-center justify-center px-3 py-1 rounded text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                            {{ $mp->category }}
                        </span>
                        @if($mp->status == 'ACTIVE')
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded text-xs font-bold bg-green-100 text-green-700 border border-green-200">ACTIVE STATUS</span>
                        @else
                            <span class="inline-flex items-center justify-center px-3 py-1 rounded text-xs font-bold bg-red-100 text-red-700 border border-red-200">{{ $mp->status }}</span>
                        @endif
                    </div>

                    <div class="border-t border-slate-100 pt-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Site</span>
                            <span class="font-semibold text-slate-800">{{ $mp->site }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">NRP</span>
                            <span class="font-mono font-semibold text-slate-800">{{ $mp->nrp ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                    <p class="text-xs font-bold text-slate-400 uppercase">Total Manhours</p>
                    <p class="text-2xl font-black text-[#002d5b]">{{ number_format($mp->manhours, 2) }}</p>
                </div>
                <div class="bg-white p-5 rounded-xl shadow-sm border border-slate-200">
                    <p class="text-xs font-bold text-slate-400 uppercase">Effective Days</p>
                    <p class="text-2xl font-black text-slate-800">{{ $mp->effective_days ?? 0 }}</p>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="font-bold text-slate-700">Contract & Employment Details</h3>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Company / Vendor</label>
                        <p class="font-semibold text-slate-800">{{ $mp->company }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Department</label>
                        <p class="font-semibold text-slate-800">{{ $mp->department ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Join Date</label>
                        <p class="font-semibold text-slate-800">{{ $mp->join_date ? date('d F Y', strtotime($mp->join_date)) : '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">End Contract</label>
                        <p class="font-semibold {{ $mp->end_date && $mp->end_date < now() ? 'text-red-600' : 'text-slate-800' }}">
                            {{ $mp->end_date ? date('d F Y', strtotime($mp->end_date)) : 'Permanent / Unspecified' }}
                        </p>
                    </div>
                    @if($mp->date_out)
                    <div class="md:col-span-2 bg-red-50 p-4 rounded border border-red-100">
                        <label class="block text-xs font-bold text-red-400 uppercase mb-1">Separation Details</label>
                        <p class="font-bold text-red-700">{{ $mp->out_reason }} ({{ date('d M Y', strtotime($mp->date_out)) }})</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection