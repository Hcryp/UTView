@extends('layout.AdmLayout')

@section('content')
<style>[x-cloak]{display:none !important}</style>

<div class="flex flex-col space-y-6 pb-12">
    <div class="flex flex-col md:flex-row justify-between items-end gap-6 shrink-0">
        <div>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Manpower Database</h2>
            <p class="text-sm text-slate-500 font-medium mt-1">Manage personnel, contracts, and operational manhours.</p>
        </div>

        <div class="flex gap-4 w-full md:w-auto">
            <div class="bg-white pl-4 pr-6 py-3 rounded-lg shadow-sm border-l-4 border-[#002d5b] flex items-center gap-4 w-full md:w-auto">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Active Personnel</p>
                    <p class="text-2xl font-black text-slate-800 leading-none">{{ number_format($summary['total_mp']) }}</p>
                </div>
            </div>
            
            <div class="bg-white pl-4 pr-6 py-3 rounded-lg shadow-sm border-l-4 border-yellow-400 flex items-center gap-4 w-full md:w-auto">
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Manhours</p>
                    <p class="text-2xl font-black text-slate-800 leading-none">{{ number_format($summary['total_mh'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div x-data="{show: true}" x-show="show" class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm flex justify-between items-center">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <button @click="show = false" class="text-green-500 hover:text-green-700">&times;</button>
    </div>
    @endif

    <div class="bg-white p-5 rounded-lg shadow-sm border border-slate-200 shrink-0">
        <form id="filterForm" method="GET" action="{{ route('manpower.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
            <input type="hidden" name="per_page" id="hidden_per_page" value="{{ request('per_page', 20) }}">

            <div class="md:col-span-3">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Search</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" id="searchInput" name="search" value="{{ request('search') }}" class="pl-9 w-full text-sm border-gray-300 rounded focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-shadow" placeholder="Name, NRP, Company..." autocomplete="off">
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Status Filter</label>
                <select id="filterStatus" name="status" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                    <option value="ACTIVE" {{ request('status', 'ACTIVE') == 'ACTIVE' ? 'selected' : '' }} class="font-bold text-green-700">Active Personnel</option>
                    <option value="ALL" {{ request('status') == 'ALL' ? 'selected' : '' }}>Show All History</option>
                    <optgroup label="Inactive / Separation">
                        @foreach($opt_out_reasons as $reason)
                            <option value="{{ $reason }}" {{ request('status') == $reason ? 'selected' : '' }}>{{ $reason }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Department</label>
                <select id="filterDept" name="department" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                    <option value="">All Departments</option>
                    @foreach($opt_depts as $dept)
                        <option value="{{ $dept }}" {{ request('department') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Category</label>
                <select id="filterCategory" name="category" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                    <option value="">All Categories</option>
                    @foreach($opt_categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-1">
                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Site</label>
                <select id="filterSite" name="site" class="w-full text-sm border-gray-300 rounded focus:ring-blue-500 cursor-pointer">
                    <option value="">All Sites</option>
                    @foreach($sites as $site) <option value="{{ $site }}" {{ request('site') == $site ? 'selected' : '' }}>{{ $site }}</option> @endforeach
                </select>
            </div>

            <div class="md:col-span-1">
                <button type="button" id="resetBtn" class="w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded text-sm font-bold transition-colors">Reset</button>
            </div>

            <div class="md:col-span-1">
                <a href="{{ route('manpower.create') }}" class="w-full flex items-center justify-center gap-2 py-2 bg-[#002d5b] hover:bg-blue-900 text-white rounded shadow-md hover:shadow-lg transition-all text-sm font-bold" title="Add New">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </a>
            </div>
        </form>
    </div>

    <div id="tableContainer" class="bg-white rounded-lg shadow-sm border border-slate-200 flex flex-col min-h-[600px] relative overflow-hidden">
        @fragment('manpower-table')
        <div class="overflow-auto grow scrollbar-thin scrollbar-thumb-slate-300 scrollbar-track-transparent">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 sticky top-0 z-10 font-bold">
                    <tr>
                        <th class="px-6 py-4 w-16 text-center">No</th>
                        <th class="px-6 py-4 w-24 text-center">Action</th>
                        <th class="px-6 py-4">Name / NRP</th>
                        <th class="px-6 py-4">Company / Site</th>
                        <th class="px-6 py-4">Role / Dept</th>
                        <th class="px-6 py-4 text-center">Joined</th>
                        <th class="px-6 py-4 text-right">Manhours</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $index => $row)
                    <tr class="group hover:bg-blue-50/50 transition-colors">
                        <td class="px-6 py-3 text-center text-slate-400 font-mono text-xs">{{ $data->firstItem() + $index }}</td>
                        <td class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                <a href="{{ route('manpower.show', $row->id) }}" class="p-1.5 text-blue-600 bg-blue-100 hover:bg-blue-200 rounded" title="View Details">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('manpower.edit', $row->id) }}" class="p-1.5 text-slate-600 bg-slate-100 hover:bg-slate-200 rounded" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            </div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="font-bold text-slate-800">{{ $row->name }}</div>
                            <div class="text-xs text-slate-500 font-mono">{{ $row->nrp ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="font-semibold text-slate-700">{{ Str::limit($row->company, 25) }}</div>
                            <div class="text-xs text-slate-500">{{ $row->site }}</div>
                        </td>
                        <td class="px-6 py-3">
                            <div class="text-slate-700 font-medium">{{ $row->role ?? '-' }}</div>
                            <div class="text-[10px] uppercase text-slate-400 font-bold tracking-wide">{{ $row->department ?? '' }}</div>
                        </td>
                        <td class="px-6 py-3 text-center text-slate-600 text-xs">
                            {{ $row->join_date ? date('M Y', strtotime($row->join_date)) : '-' }}
                        </td>
                        <td class="px-6 py-3 text-right font-mono font-bold text-slate-700">
                            {{ number_format($row->manhours, 1) }}
                        </td>
                        <td class="px-6 py-3 text-center">
                            @if($row->status == 'ACTIVE')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-green-100 text-green-800 border border-green-200">
                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full mr-1.5"></span> ACTIVE
                                </span>
                            @else
                                <div class="flex flex-col items-center">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-red-100 text-red-800 border border-red-200">
                                        {{ $row->out_reason ?? 'INACTIVE' }}
                                    </span>
                                    @if($row->date_out)
                                    <span class="text-[10px] text-red-400 mt-0.5">{{ date('d/m/y', strtotime($row->date_out)) }}</span>
                                    @endif
                                </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="px-6 py-24 text-center text-slate-400 font-medium">No personnel found matching criteria.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-slate-50 border-t border-slate-200 px-6 py-3 flex justify-between items-center">
            <div class="text-xs text-slate-500">Showing {{ $data->firstItem() }}-{{ $data->lastItem() }} of <strong>{{ $data->total() }}</strong></div>
            <div class="scale-90">{{ $data->appends(request()->query())->links() }}</div>
        </div>
        @endfragment
    </div>
</div>

<div x-data="{ open: false }" class="fixed bottom-0 right-8 z-40 print:hidden">
    <button @click="open = !open" class="bg-slate-800 hover:bg-slate-700 text-white px-5 py-3 rounded-t-lg shadow-lg flex items-center gap-3 transition-colors text-xs font-bold uppercase tracking-wider border border-slate-700">
        <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        Detailed Report
        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-300 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
    </button>

    <div x-show="open" x-cloak x-transition.origin.bottom class="w-[600px] h-[500px] bg-white shadow-2xl border border-slate-200 rounded-tl-lg flex flex-col overflow-hidden">
        <div class="bg-slate-100 p-4 border-b border-slate-200 flex justify-between items-center shrink-0">
            <h3 class="font-bold text-slate-700 text-xs uppercase tracking-wide">Category Breakdown</h3>
            <button @click="open = false" class="text-slate-400 hover:text-red-500"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="overflow-y-auto p-4 space-y-6 scrollbar-thin bg-slate-50 h-full">
            <table class="w-full text-xs bg-white shadow-sm rounded overflow-hidden">
                <thead class="bg-blue-50 text-blue-800 border-b border-blue-100"><tr><th class="p-3 text-left">Category</th><th class="p-3 text-right">MP</th><th class="p-3 text-right">Hours</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($highLevelTable as $row)
                    <tr><td class="p-3 font-bold">{{ $row->category }}</td><td class="p-3 text-right">{{ $row->mp }}</td><td class="p-3 text-right font-mono">{{ number_format($row->mh) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
            <table class="w-full text-xs bg-white shadow-sm rounded overflow-hidden">
                <thead class="bg-yellow-50 text-yellow-800 border-b border-yellow-100"><tr><th class="p-3 text-left">Company Detail</th><th class="p-3 text-right">MP</th><th class="p-3 text-right">Hours</th></tr></thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($detailedTable as $row)
                    <tr><td class="p-3"><span class="block font-bold text-slate-700">{{ $row->company }}</span><span class="text-[10px] text-slate-400">{{ $row->category }}</span></td><td class="p-3 text-right">{{ $row->mp }}</td><td class="p-3 text-right font-mono">{{ number_format($row->mh) }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://unpkg.com/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const f=document.getElementById('filterForm'),c=document.getElementById('tableContainer');
    let t,ac;
    function q(p){
        if(ac)ac.abort();
        ac=new AbortController();
        let params=p||new URLSearchParams(new FormData(f));
        const pp=document.getElementById('per_page_select');
        if(!p&&pp)params.append('per_page',pp.value);
        let u="{{ route('manpower.index') }}?"+params.toString();
        c.classList.add('opacity-50','pointer-events-none');
        fetch(u,{headers:{'X-Requested-With':'XMLHttpRequest'},signal:ac.signal})
        .then(r=>r.text()).then(h=>{
            c.innerHTML=h;c.classList.remove('opacity-50','pointer-events-none');
            window.history.replaceState({},'',u);ac=null;l();
        }).catch(e=>{if(e.name!=='AbortError'){c.classList.remove('opacity-50','pointer-events-none');console.error(e)}});
    }
    function l(){
        c.querySelectorAll('.pagination a').forEach(a=>a.addEventListener('click',e=>{e.preventDefault();q(new URL(a.href).searchParams)}));
        const pp=document.getElementById('per_page_select');if(pp)pp.addEventListener('change',()=>q());
    }
    ['searchInput','filterSite','filterStatus','filterCategory','filterDept'].forEach(i=>{
        const e=document.getElementById(i);if(e)e.addEventListener(e.type==='text'?'input':'change',()=>{clearTimeout(t);t=setTimeout(()=>q(),600)});
    });
    document.getElementById('resetBtn').addEventListener('click',()=>{if(ac)ac.abort();f.reset();q()});
    l();
});
</script>
@endsection