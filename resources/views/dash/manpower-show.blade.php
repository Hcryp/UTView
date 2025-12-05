@extends('layout.AdmLayout')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <a href="{{ route('manpower.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#002d5b] uppercase tracking-wide">&larr; Back to List</a>
            <h2 class="text-3xl font-extrabold text-slate-800 mt-1">{{ $mp->name }}</h2>
            <div class="flex items-center gap-3 mt-2">
                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase {{ $mp->status === 'ACTIVE' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                    {{ $mp->status }}
                </span>
                <span class="text-slate-400 text-sm">&bull;</span>
                <span class="text-sm font-medium text-slate-600">{{ $mp->nrp ?? 'No ID' }}</span>
                <span class="text-slate-400 text-sm">&bull;</span>
                <span class="text-sm font-medium text-slate-500">{{ $mp->role ?? 'No Role' }}</span>
            </div>
        </div>
        <div class="flex gap-3">
            <form action="{{ route('manpower.destroy', $mp->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this record?');">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-white border border-red-200 text-red-600 rounded shadow-sm text-xs font-bold hover:bg-red-50 transition">
                    Delete
                </button>
            </form>
            <a href="{{ route('manpower.edit', $mp->id) }}" class="px-6 py-2 bg-[#002d5b] text-white rounded shadow-sm text-xs font-bold hover:bg-blue-900 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Details
            </a>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        {{-- Left Column: Basic Info --}}
        <div class="md:col-span-2 space-y-6">
            {{-- Employment Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Employment Information</h3>
                    @if($mp->company)
                        <span class="text-[10px] font-bold px-2 py-1 bg-blue-50 text-blue-700 rounded border border-blue-100">{{ $mp->company }}</span>
                    @endif
                </div>
                <div class="p-6 grid grid-cols-2 gap-y-6 gap-x-4">
                    <div>
                        <span class="block text-xs text-slate-400 font-medium mb-1">Site</span>
                        <span class="block text-sm font-bold text-slate-700">{{ $mp->site }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-400 font-medium mb-1">Department</span>
                        <span class="block text-sm font-bold text-slate-700">{{ $mp->department ?? '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-400 font-medium mb-1">Category</span>
                        <span class="block text-sm font-bold text-slate-700">{{ $mp->category }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-400 font-medium mb-1">Job Title</span>
                        <span class="block text-sm font-bold text-slate-700">{{ $mp->role ?? '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Contract & Dates Card --}}
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider">Contract Details</h3>
                </div>
                <div class="p-6 grid grid-cols-3 gap-6">
                    <div>
                        <span class="block text-xs text-slate-400 font-medium mb-1">Join Date</span>
                        <span class="block text-sm font-bold text-slate-700">{{ $mp->join_date ? date('d M Y', strtotime($mp->join_date)) : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-400 font-medium mb-1">End Date</span>
                        <span class="block text-sm font-bold {{ $mp->end_date && strtotime($mp->end_date) < time() ? 'text-red-600' : 'text-slate-700' }}">
                            {{ $mp->end_date ? date('d M Y', strtotime($mp->end_date)) : 'Permanent' }}
                        </span>
                    </div>
                    <div>
                        <span class="block text-xs text-slate-400 font-medium mb-1">Manhours</span>
                        <span class="block text-sm font-bold font-mono text-slate-700">{{ number_format($mp->manhours, 2) }}</span>
                    </div>
                </div>
            </div>

            {{-- Separation Details (Only if INACTIVE) --}}
            @if($mp->status === 'INACTIVE')
            <div class="bg-red-50 rounded-xl shadow-sm border border-red-100 overflow-hidden relative">
                <div class="absolute top-0 right-0 p-4 opacity-10">
                    <svg class="w-24 h-24 text-red-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                </div>
                <div class="px-6 py-4 border-b border-red-100 bg-red-100/50">
                    <h3 class="text-xs font-bold text-red-700 uppercase tracking-wider">Separation Information</h3>
                </div>
                <div class="p-6 relative z-10 grid grid-cols-2 gap-6">
                    <div>
                        <span class="block text-xs text-red-400 font-bold mb-1">Date Out</span>
                        <span class="block text-lg font-bold text-red-800">{{ $mp->date_out ? date('d M Y', strtotime($mp->date_out)) : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-red-400 font-bold mb-1">Reason</span>
                        <span class="block text-lg font-bold text-red-800">{{ $mp->out_reason ?? '-' }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Right Column: Stats/Meta --}}
        <div class="space-y-6">
            <div class="bg-[#002d5b] rounded-xl shadow-lg p-6 text-white relative overflow-hidden">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                <h3 class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-4">Quick Stats</h3>
                
                <div class="mb-6">
                    <div class="text-3xl font-extrabold">{{ $mp->effective_days }}</div>
                    <div class="text-xs text-blue-200">Effective Working Days</div>
                </div>

                <div class="pt-4 border-t border-white/10">
                    <div class="text-xs text-blue-300 mb-1">Last Updated</div>
                    <div class="text-sm font-medium">{{ $mp->updated_at->diffForHumans() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection