@extends('layout.AdmLayout')

@section('content')
<div class="flex flex-col space-y-4 pb-12"> 
    
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 shrink-0">
        <div>
            <h2 class="text-2xl font-bold text-slate-800 tracking-tight">Manpower Database</h2>
            <p class="text-sm text-slate-500 font-medium">
                Data Management & Realtime Analytics
            </p>
        </div>

        <div class="flex gap-4">
            <div class="bg-white px-6 py-4 rounded-xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="p-3 bg-blue-50 text-blue-700 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Manpower</p>
                    <p class="text-2xl font-extrabold text-slate-800 leading-none">{{ number_format($summary['total_mp']) }}</p>
                </div>
            </div>
            
            <div class="bg-white px-6 py-4 rounded-xl shadow-sm border border-slate-200 flex items-center gap-4">
                <div class="p-3 bg-yellow-50 text-yellow-600 rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Manhours</p>
                    <p class="text-2xl font-extrabold text-slate-800 leading-none">{{ number_format($summary['total_mh'], 2) }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white p-4 rounded-xl shadow-sm border border-slate-200 shrink-0">
        <form id="filterForm" method="GET" action="{{ route('manpower.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            
            <input type="hidden" name="per_page" id="hidden_per_page" value="{{ request('per_page', 20) }}">

            <div class="md:col-span-4 relative group">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" id="searchInput" name="search" value="{{ request('search') }}" 
                    class="pl-10 w-full text-sm border-gray-300 bg-white rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 transition-all placeholder-gray-400 py-2.5" 
                    placeholder="Search Name, NRP, Company..." autocomplete="off">
            </div>

            <div class="md:col-span-2">
                <select id="filterSite" name="site" class="w-full text-sm border-gray-300 bg-white rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 cursor-pointer py-2.5">
                    <option value="">All Sites</option>
                    @foreach($sites as $site)
                        <option value="{{ $site }}" {{ request('site') == $site ? 'selected' : '' }}>{{ $site }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <select id="filterStatus" name="status" class="w-full text-sm border-gray-300 bg-white rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 cursor-pointer py-2.5">
                    <option value="ACTIVE" {{ request('status', 'ACTIVE') == 'ACTIVE' ? 'selected' : '' }}>Active Only</option>
                    <option value="ALL" {{ request('status') == 'ALL' ? 'selected' : '' }}>Show All</option>
                    <option value="RESIGN" {{ request('status') == 'RESIGN' ? 'selected' : '' }}>Resigned</option>
                    <option value="MUTASI" {{ request('status') == 'MUTASI' ? 'selected' : '' }}>Mutasi</option>
                </select>
            </div>

            <div class="md:col-span-3">
                <select id="filterCategory" name="category" class="w-full text-sm border-gray-300 bg-white rounded-lg focus:ring-2 focus:ring-blue-100 focus:border-blue-500 cursor-pointer py-2.5">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="md:col-span-1">
                <button type="button" id="resetBtn" class="w-full flex items-center justify-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-600 rounded-lg transition-colors font-medium text-sm" title="Reset Filters">
                    Reset
                </button>
            </div>

        </form>
    </div>

    <div id="tableContainer" class="bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col min-h-[600px] relative">
        
        @fragment('manpower-table')
        
        <div class="overflow-auto grow scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
            <table class="w-full text-sm text-left whitespace-nowrap min-w-max">
                <thead class="text-slate-600 font-bold uppercase bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm text-xs">
                    <tr>
                        <th class="px-6 py-4 w-14 text-center bg-slate-50">No</th>
                        <th class="px-6 py-4 bg-slate-50">Site</th>
                        <th class="px-6 py-4 bg-slate-50">Perusahaan</th>
                        <th class="px-6 py-4 bg-slate-50">NRP</th>
                        <th class="px-6 py-4 bg-slate-50">Nama</th>
                        <th class="px-6 py-4 bg-slate-50">Departemen</th>
                        <th class="px-6 py-4 bg-slate-50">Jabatan</th>
                        <th class="px-6 py-4 text-center bg-slate-50">Mulai Kontrak</th>
                        <th class="px-6 py-4 text-center bg-slate-50">Akhir Kontrak</th>
                        <th class="px-6 py-4 text-center bg-slate-50">Hari Efektif</th>
                        <th class="px-6 py-4 text-right bg-slate-50">Manhours</th>
                        <th class="px-6 py-4 text-center bg-slate-50">Tanggal Out</th>
                        <th class="px-6 py-4 bg-slate-50">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($data as $index => $row)
                    <tr class="bg-white hover:bg-blue-50/60 transition-colors group text-slate-700">
                        <td class="px-6 py-3.5 text-center text-slate-400 font-mono">{{ $data->firstItem() + $index }}</td>
                        <td class="px-6 py-3.5 font-bold text-slate-600">{{ $row->site }}</td>
                        <td class="px-6 py-3.5 font-semibold" title="{{ $row->company }}">{{ Str::limit($row->company, 25) }}</td>
                        <td class="px-6 py-3.5 font-mono text-slate-600">{{ $row->nrp ?? '-' }}</td>
                        <td class="px-6 py-3.5 font-bold text-slate-900">{{ $row->name }}</td>
                        <td class="px-6 py-3.5 text-slate-600">{{ $row->department ?? '-' }}</td>
                        <td class="px-6 py-3.5">
                            <span class="text-xs font-semibold text-slate-700 bg-slate-100 px-2.5 py-1 rounded border border-slate-200">
                                {{ $row->role ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-3.5 text-center text-slate-500">{{ $row->join_date ? date('d M Y', strtotime($row->join_date)) : '-' }}</td>
                        <td class="px-6 py-3.5 text-center">
                            @if($row->end_date)
                                <span class="text-red-600 font-medium bg-red-50 px-2 py-0.5 rounded text-xs">{{ date('d M Y', strtotime($row->end_date)) }}</span>
                            @elseif($row->category == 'KARYAWAN')
                                <span class="text-green-700 font-bold text-xs bg-green-50 px-2 py-0.5 rounded border border-green-100">PERMANENT</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-center font-mono text-slate-700 font-medium">{{ $row->effective_days > 0 ? $row->effective_days : '-' }}</td>
                        <td class="px-6 py-3.5 text-right font-mono font-bold text-slate-800 bg-slate-50/50">{{ number_format($row->manhours, 2) }}</td>
                        <td class="px-6 py-3.5 text-center text-red-500 font-medium">{{ $row->date_out ? date('d M Y', strtotime($row->date_out)) : '-' }}</td>
                        <td class="px-6 py-3.5">
                            @if($row->out_reason)
                                <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded border border-red-200 font-bold uppercase tracking-wide">{{ $row->out_reason }}</span>
                            @else
                                <span class="text-xs text-green-700 bg-green-100 px-2 py-1 rounded border border-green-200 font-bold uppercase tracking-wide">Active</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="13" class="px-6 py-24 text-center">
                            <div class="flex flex-col items-center justify-center text-slate-400">
                                <svg class="w-16 h-16 mb-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-lg font-medium text-slate-500">No data found matching your filters.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-auto px-6 py-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4 text-sm text-slate-500">
                <span>Showing <span class="font-bold text-slate-800">{{ $data->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-800">{{ $data->lastItem() ?? 0 }}</span> of <span class="font-bold text-slate-800">{{ $data->total() }}</span> entries</span>
                
                <span class="h-4 w-px bg-slate-300 mx-2 hidden sm:block"></span>
                
                <div class="flex items-center gap-2">
                    <label for="per_page" class="text-xs font-medium uppercase text-slate-400">Show</label>
                    <select id="per_page_select" class="text-xs border-slate-300 rounded bg-white py-1 pr-6 pl-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        <option value="20" {{ request('per_page', 20) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </div>
            <div class="w-full sm:w-auto scale-90 origin-right">
                {{ $data->appends(request()->query())->links() }}
            </div>
        </div>

        @endfragment
        </div>
</div>

<div x-data="{ open: false }" class="fixed bottom-0 right-8 z-50 flex flex-col items-end print:hidden">
    <button @click="open = !open" class="bg-slate-800 text-white px-6 py-3 rounded-t-lg shadow-2xl flex items-center gap-3 hover:bg-slate-700 transition-colors text-sm font-bold tracking-wide border-t border-l border-r border-slate-600">
        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        <span>Summary Report</span>
        <svg :class="{'rotate-180': open}" class="w-4 h-4 transition-transform duration-300 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
    </button>

    <div x-show="open" x-transition class="w-[800px] h-[600px] bg-white shadow-2xl border border-slate-200 rounded-tl-lg flex flex-col overflow-hidden" style="display: none;">
        <div class="bg-slate-100 p-4 border-b border-slate-200 flex justify-between items-center shrink-0">
            <h3 class="font-bold text-slate-700 text-sm uppercase tracking-wide">Detailed Summary Data</h3>
            <button @click="open = false" class="text-slate-400 hover:text-slate-600"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="overflow-y-auto p-6 space-y-8 scrollbar-thin scrollbar-thumb-gray-300 bg-slate-50">
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-blue-50 px-4 py-2 border-b border-blue-100"><h4 class="font-bold text-blue-800 text-xs uppercase">1. Summary By Category</h4></div>
                <table class="w-full text-xs text-left"><thead class="bg-slate-50 text-slate-600 font-semibold border-b"><tr><th class="p-3">Category</th><th class="p-3 text-right">Manpower</th><th class="p-3 text-right">Manhours</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach($highLevelTable as $row)<tr class="hover:bg-slate-50"><td class="p-3 font-medium text-slate-700">{{ $row->category }}</td><td class="p-3 text-right font-bold">{{ $row->mp }}</td><td class="p-3 text-right text-slate-500 font-mono">{{ number_format($row->mh, 0) }}</td></tr>@endforeach</tbody></table>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
                <div class="bg-yellow-50 px-4 py-2 border-b border-yellow-100"><h4 class="font-bold text-yellow-800 text-xs uppercase">2. Detailed Breakdown (By Company)</h4></div>
                <table class="w-full text-xs text-left"><thead class="bg-slate-50 text-slate-600 font-semibold border-b"><tr><th class="p-3">Category</th><th class="p-3">Company</th><th class="p-3 text-right">MP</th><th class="p-3 text-right">MH</th></tr></thead><tbody class="divide-y divide-slate-100">@foreach($detailedTable as $row)<tr class="hover:bg-slate-50"><td class="p-3 text-[10px] text-slate-500 uppercase tracking-wide">{{ $row->category }}</td><td class="p-3 font-bold text-slate-700">{{ $row->company }}</td><td class="p-3 text-right font-bold">{{ $row->mp }}</td><td class="p-3 text-right text-slate-500 font-mono">{{ number_format($row->mh, 0) }}</td></tr>@endforeach</tbody></table>
            </div>
        </div>
        <div class="bg-slate-900 text-white p-4 text-sm flex justify-between items-center shrink-0 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.1)]"><span class="font-bold text-slate-300 uppercase tracking-wider text-xs">Grand Total</span><div class="flex gap-8"><div class="flex flex-col items-end"><span class="text-[10px] text-slate-400 uppercase">Manpower</span><strong class="text-yellow-400 text-lg leading-none">{{ number_format($summary['total_mp']) }}</strong></div><div class="flex flex-col items-end"><span class="text-[10px] text-slate-400 uppercase">Manhours</span><strong class="text-yellow-400 text-lg leading-none">{{ number_format($summary['total_mh'], 2) }}</strong></div></div></div>
    </div>
</div>

<script src="//unpkg.com/alpinejs" defer></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filterForm');
        const tableContainer = document.getElementById('tableContainer');
        const resetBtn = document.getElementById('resetBtn');
        
        let debounceTimer;
        let abortController = null; // Variable to store the current request controller

        // Function to fetch data via AJAX (only refreshing the table fragment)
        function fetchResults(customParams = null) {
            // 1. Cancel the previous request if it's still running
            if (abortController) {
                abortController.abort();
            }
            // 2. Create a new AbortController for the new request
            abortController = new AbortController();

            let params = customParams;
            if (!params) {
                const formData = new FormData(form);
                const perPageVal = document.getElementById('per_page_select')?.value;
                if(perPageVal) {
                    formData.set('per_page', perPageVal);
                    document.getElementById('hidden_per_page').value = perPageVal;
                }
                params = new URLSearchParams(formData).toString();
            }

            const url = "{{ route('manpower.index') }}?" + params;

            // Visual feedback
            tableContainer.classList.add('opacity-50', 'pointer-events-none');

            fetch(url, { 
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                signal: abortController.signal // 3. Attach the signal to the fetch request
            })
            .then(res => res.text())
            .then(html => {
                tableContainer.innerHTML = html;
                tableContainer.classList.remove('opacity-50', 'pointer-events-none');
                window.history.replaceState({}, '', url);
                attachTableListeners();
                abortController = null; // Reset controller after success
            })
            .catch(err => {
                // 4. Ignore errors caused by aborting
                if (err.name === 'AbortError') {
                    console.log('Previous search cancelled to save data.');
                    return; 
                }
                console.error('Error fetching data:', err);
                tableContainer.classList.remove('opacity-50', 'pointer-events-none');
            });
        }

        // 1. Debounced Search Input
        ['searchInput', 'filterSite', 'filterStatus', 'filterCategory'].forEach(id => {
            const el = document.getElementById(id);
            if(el) {
                el.addEventListener(el.type === 'text' ? 'input' : 'change', () => {
                    clearTimeout(debounceTimer);
                    // Increased delay to 600ms to reduce requests while typing fast
                    debounceTimer = setTimeout(() => fetchResults(), 600); 
                });
            }
        });

        // 2. Pagination & Per Page Listeners
        function attachTableListeners() {
            tableContainer.querySelectorAll('.pagination a').forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const url = new URL(this.href);
                    fetchResults(url.searchParams.toString());
                });
            });

            const perPageSelect = document.getElementById('per_page_select');
            if(perPageSelect) {
                perPageSelect.addEventListener('change', () => fetchResults());
            }
        }

        // 3. Reset Button
        resetBtn.addEventListener('click', (e) => {
            e.preventDefault();
            // Cancel any ongoing search before resetting
            if (abortController) abortController.abort();
            form.reset();
            fetchResults();
        });

        attachTableListeners();
    });
</script>
@endsection