@extends('layout.AdmLayout')

@section('content')
<div class="flex flex-col h-[calc(100vh-100px)]"> 
    
    <div class="mb-6 flex flex-col md:flex-row justify-between items-end gap-4 shrink-0">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Manpower Database</h2>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider mt-1">
                Data Management & Realtime Analytics
            </p>
        </div>

        <div class="flex gap-4">
            <div class="bg-white px-5 py-3 rounded-xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="p-2 bg-blue-50 text-blue-700 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Manpower</p>
                    <p class="text-lg font-extrabold text-slate-800 leading-none">{{ number_format($summary['total_mp']) }}</p>
                </div>
            </div>
            
            <div class="bg-white px-5 py-3 rounded-xl shadow-sm border border-slate-100 flex items-center gap-4">
                <div class="p-2 bg-yellow-50 text-yellow-600 rounded-lg">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Total Manhours</p>
                    <p class="text-lg font-extrabold text-slate-800 leading-none">{{ number_format($summary['total_mh'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-3 rounded-xl shadow-sm border border-slate-200 mb-4 shrink-0">
        <form id="filterForm" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            
            <div class="md:col-span-4 relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                    class="pl-10 w-full text-sm border-gray-200 bg-gray-50 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all placeholder-gray-400" 
                    placeholder="Search Name, NRP, Company..." autocomplete="off">
            </div>

            <div class="md:col-span-2">
                <select id="filterSite" name="site" class="w-full text-sm border-gray-200 bg-gray-50 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 cursor-pointer">
                    <option value="">🌍 All Sites</option>
                    @foreach($sites as $site)
                        <option value="{{ $site }}" {{ request('site') == $site ? 'selected' : '' }}>{{ $site }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <select id="filterStatus" name="status" class="w-full text-sm border-gray-200 bg-gray-50 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 cursor-pointer">
                    <option value="ACTIVE" {{ request('status', 'ACTIVE') == 'ACTIVE' ? 'selected' : '' }}>🟢 Active</option>
                    <option value="ALL" {{ request('status') == 'ALL' ? 'selected' : '' }}>⚪ Show All</option>
                    <option value="RESIGN" {{ request('status') == 'RESIGN' ? 'selected' : '' }}>🔴 Resigned</option>
                    <option value="MUTASI" {{ request('status') == 'MUTASI' ? 'selected' : '' }}>🔵 Mutasi</option>
                </select>
            </div>

            <div class="md:col-span-3">
                <select id="filterCategory" name="category" class="w-full text-sm border-gray-200 bg-gray-50 rounded-lg focus:bg-white focus:ring-2 focus:ring-blue-100 focus:border-blue-500 cursor-pointer">
                    <option value="">📂 All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-1">
                <button type="button" id="resetBtn" class="w-full flex items-center justify-center px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-500 rounded-lg transition-colors" title="Reset Filters">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

        </form>
    </div>

    <div id="tableContainer" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden flex flex-col grow relative">
        @include('dash.manpower_table')
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = ['searchInput', 'filterSite', 'filterStatus', 'filterCategory'];
        const form = document.getElementById('filterForm');
        const tableContainer = document.getElementById('tableContainer');
        const resetBtn = document.getElementById('resetBtn');
        let debounceTimer;

        function fetchResults(url = null) {
            if (!url) {
                const params = new URLSearchParams(new FormData(form)).toString();
                url = "{{ route('manpower.index') }}?" + params;
            }

            tableContainer.style.opacity = '0.6';
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(res => res.text())
            .then(html => {
                tableContainer.innerHTML = html;
                tableContainer.style.opacity = '1';
                window.history.replaceState({}, '', url);
            });
        }

        inputs.forEach(id => {
            const el = document.getElementById(id);
            el.addEventListener(el.type === 'text' ? 'input' : 'change', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => fetchResults(), 300);
            });
        });

        tableContainer.addEventListener('click', function(e) {
            const link = e.target.closest('.pagination a');
            if (link) {
                e.preventDefault();
                fetchResults(link.href);
            }
        });

        resetBtn.addEventListener('click', () => {
            form.reset();
            fetchResults();
        });
    });
</script>
@endsection