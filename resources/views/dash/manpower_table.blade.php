<div class="overflow-auto grow scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-transparent">
    <table class="w-full text-sm text-left table-fixed">
        <thead class="text-xs text-slate-500 font-bold uppercase bg-slate-50 border-b border-slate-200 sticky top-0 z-10 shadow-sm">
            <tr>
                <th class="px-4 py-4 whitespace-nowrap bg-slate-50 w-[5%] text-center">No</th>
                <th class="px-4 py-4 whitespace-nowrap bg-slate-50 w-[18%]">Site / Company</th>
                <th class="px-4 py-4 whitespace-nowrap bg-slate-50 w-[20%]">Identity (NRP / Name)</th>
                <th class="px-4 py-4 whitespace-nowrap bg-slate-50 w-[15%]">Position (Jabatan)</th>
                <th class="px-4 py-4 whitespace-nowrap bg-slate-50 w-[12%]">Category</th>
                <th class="px-4 py-4 text-center whitespace-nowrap bg-slate-50 w-[15%]">Contract Period</th>
                <th class="px-4 py-4 text-right whitespace-nowrap bg-slate-50 w-[15%]">Manhours</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-slate-100">
            @forelse($data as $index => $row)
            <tr class="bg-white hover:bg-blue-50/50 transition-colors group">
                
                <td class="px-4 py-3 text-center text-xs text-slate-400 font-mono">
                    {{ $data->firstItem() + $index }}
                </td>

                <td class="px-4 py-3 truncate">
                    <div class="flex flex-col items-start gap-0.5">
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded border 
                            {{ $row->site == 'SATUI' ? 'bg-indigo-50 text-indigo-700 border-indigo-100' : 'bg-orange-50 text-orange-700 border-orange-100' }}">
                            {{ $row->site }}
                        </span>
                        <div class="text-xs font-bold text-slate-700 truncate w-full" title="{{ $row->company }}">
                            {{ $row->company }}
                        </div>
                    </div>
                </td>

                <td class="px-4 py-3 truncate">
                    <div class="flex flex-col">
                        <div class="font-bold text-slate-800 text-sm truncate" title="{{ $row->name }}">{{ $row->name }}</div>
                        <div class="text-[11px] text-slate-400 font-mono flex items-center gap-1">
                            <span class="text-slate-300">#</span> {{ $row->nrp ?? '-' }}
                        </div>
                    </div>
                </td>

                <td class="px-4 py-3 truncate">
                    <span class="text-xs font-semibold text-slate-700 bg-slate-50 px-2 py-1 rounded border border-slate-100 truncate inline-block max-w-full" title="{{ $row->role }}">
                        {{ $row->role ?? '-' }}
                    </span>
                </td>

                <td class="px-4 py-3 truncate">
                    <span class="text-[10px] font-medium text-slate-600 bg-slate-100 px-2 py-1 rounded truncate block text-center" title="{{ $row->category }}">
                        {{ Str::limit($row->category, 15) }}
                    </span>
                </td>

                <td class="px-4 py-3 text-center">
                    <div class="flex flex-col text-[10px]">
                        @if($row->join_date)
                            <span class="text-slate-600">
                                <span class="text-slate-400">In:</span> {{ date('d M Y', strtotime($row->join_date)) }}
                            </span>
                        @endif
                        
                        @if($row->end_date)
                            <span class="text-red-500 font-medium">
                                <span class="text-red-300">Out:</span> {{ date('d M Y', strtotime($row->end_date)) }}
                            </span>
                        @elseif($row->category == 'KARYAWAN')
                            <span class="text-green-600 font-bold text-[9px]">PERMANENT</span>
                        @endif
                    </div>
                </td>

                <td class="px-4 py-3 text-right">
                    <div class="font-mono font-bold text-slate-700">{{ number_format($row->manhours, 2) }}</div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-20 text-center">
                    <div class="flex flex-col items-center justify-center text-slate-300">
                        <svg class="w-12 h-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        <p class="text-sm font-medium text-slate-400">No data found</p>
                        <p class="text-xs text-slate-400 mt-1">Try changing your filters.</p>
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="p-3 border-t border-slate-100 bg-white flex justify-between items-center shrink-0">
    <div class="text-xs text-slate-500">
        Showing <span class="font-bold text-slate-700">{{ $data->firstItem() ?? 0 }}</span> to <span class="font-bold text-slate-700">{{ $data->lastItem() ?? 0 }}</span>
    </div>
    <div class="scale-90 origin-right">
        {{ $data->links() }}
    </div>
</div>