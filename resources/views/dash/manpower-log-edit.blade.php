@extends('layout.AdmLayout')
@section('content')
<div class="flex flex-col space-y-6 pb-12" x-data="logEditor()">
    <form action="{{ route('manpower.log.update', $log->id) }}" method="POST">
        @csrf @method('PUT')
        
        <div class="flex justify-between items-center border-b border-slate-200 pb-5 sticky top-0 bg-gray-100 z-20 pt-2">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Audit Daily Recap</h2>
                <p class="text-sm text-slate-500 mt-1">
                    Log Date: <span class="font-mono font-bold text-slate-700">{{ $log->log_date->format('d M Y') }}</span>
                </p>
            </div>
            <div class="flex items-center gap-4 bg-white p-2 rounded-lg shadow-sm border border-slate-200">
                <div class="text-right px-2">
                    <div class="text-[10px] text-slate-400 font-bold uppercase">Total MP</div>
                    <div class="text-lg font-black text-slate-800" x-text="totalMp"></div>
                </div>
                <div class="text-right border-l border-slate-200 pl-4 pr-2">
                    <div class="text-[10px] text-slate-400 font-bold uppercase">Total Daily Hours</div>
                    <div class="text-lg font-black text-blue-600" x-text="formatNumber(totalMh)"></div>
                </div>
                <div class="border-l border-slate-200 pl-4 flex gap-2">
                    <a href="{{ route('manpower.log.show', $log->id) }}" class="px-3 py-2 text-slate-500 hover:text-slate-700 font-bold text-xs bg-gray-50 hover:bg-gray-100 border border-slate-300 rounded transition">Cancel</a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded text-xs font-bold transition shadow-sm">Save Audit</button>
                </div>
            </div>
        </div>

        @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm mb-6">
            <ul class="list-disc pl-5 text-xs">@foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
        @endif

        <div class="bg-white rounded-lg shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-auto max-h-[70vh]">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 font-bold sticky top-0 z-10">
                        <tr>
                            <th class="px-4 py-3 w-10 text-center">#</th>
                            <th class="px-4 py-3 min-w-[200px]">Identity</th>
                            <th class="px-4 py-3 min-w-[150px]">Assignment</th>
                            <th class="px-4 py-3 w-24 text-right bg-gray-50/50">Prev. MH</th>
                            <th class="px-4 py-3 w-24 text-right bg-gray-50/50">Curr. MH</th>
                            <th class="px-4 py-3 w-28 text-right text-blue-700 bg-blue-50/30">Daily (Delta)</th>
                            <th class="px-2 py-3 w-10"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <template x-for="(item, index) in items" :key="index">
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-4 py-2 text-center text-slate-400 font-mono text-xs" x-text="index + 1"></td>
                                
                                <input type="hidden" :name="'items['+index+'][id]'" :value="item.id">
                                <input type="hidden" :name="'items['+index+'][join_date]'" :value="item.join_date">
                                <input type="hidden" :name="'items['+index+'][end_date]'" :value="item.end_date">
                                <input type="hidden" :name="'items['+index+'][status]'" :value="item.status">
                                <input type="hidden" :name="'items['+index+'][out_reason]'" :value="item.out_reason">
                                <input type="hidden" :name="'items['+index+'][previous_mh]'" :value="item.previous_mh">

                                <td class="px-4 py-2">
                                    <input type="text" :name="'items['+index+'][name]'" x-model="item.name" class="w-full text-sm font-bold text-slate-700 border-0 border-b border-dashed border-slate-200 focus:border-indigo-500 focus:ring-0 bg-transparent px-0 py-0.5 mb-1" placeholder="Name">
                                    <input type="text" :name="'items['+index+'][nrp]'" x-model="item.nrp" class="w-full text-xs font-mono text-slate-400 border-0 focus:ring-0 bg-transparent px-0 py-0" placeholder="NRP">
                                </td>
                                <td class="px-4 py-2">
                                    <div class="grid gap-1">
                                        <input type="text" :name="'items['+index+'][company]'" x-model="item.company" class="w-full text-xs font-semibold text-slate-600 border-0 bg-transparent px-0 py-0 focus:ring-0" placeholder="Company">
                                        <div class="flex gap-2">
                                            <input type="text" :name="'items['+index+'][site]'" x-model="item.site" class="w-1/2 text-[10px] text-slate-400 border-0 bg-transparent px-0 py-0 focus:ring-0 uppercase" placeholder="SITE">
                                            <input type="text" :name="'items['+index+'][category]'" x-model="item.category" class="w-1/2 text-[10px] text-slate-400 border-0 bg-transparent px-0 py-0 focus:ring-0 text-right" placeholder="CAT">
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="px-4 py-2 text-right font-mono text-xs text-slate-400 bg-gray-50/50">
                                    <span x-text="formatNumber(item.previous_mh)"></span>
                                </td>
                                <td class="px-4 py-2 text-right bg-gray-50/50">
                                    <input type="number" step="0.01" :name="'items['+index+'][cumulative_mh]'" x-model.number="item.cumulative_mh" 
                                           @input="updateDelta(index)"
                                           class="w-full text-right text-xs font-mono text-slate-500 border-0 border-b border-dashed border-slate-300 focus:border-indigo-500 focus:ring-0 bg-transparent px-0 py-1">
                                </td>

                                <td class="px-4 py-2 bg-blue-50/30">
                                    <input type="number" step="0.01" :name="'items['+index+'][manhours]'" x-model.number="item.manhours" 
                                           @input="updateCumulative(index)"
                                           class="w-full text-right text-sm font-mono font-bold text-blue-700 border border-blue-200 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500 py-1 px-2 bg-white shadow-sm">
                                </td>

                                <td class="px-2 py-2 text-center">
                                    <button type="button" @click="remove(index)" class="text-slate-300 hover:text-red-500 transition-colors p-1 rounded hover:bg-red-50" title="Remove Entry">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        <tr x-show="items.length === 0">
                            <td colspan="8" class="p-8 text-center text-slate-400 italic">No data in this log.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<script src="//unpkg.com/alpinejs" defer></script>
<script>
function logEditor(){
    return {
        items:@json($log->content).map(i=>({...i,previous_mh:parseFloat(i.previous_mh)||0,cumulative_mh:parseFloat(i.cumulative_mh)||0,manhours:parseFloat(i.manhours)||0})),
        get totalMp(){return this.items.length},
        get totalMh(){return this.items.reduce((s,i)=>s+(parseFloat(i.manhours)||0),0)},
        updateCumulative(idx){const i=this.items[idx];i.cumulative_mh=(i.previous_mh+parseFloat(i.manhours||0)).toFixed(2)},
        updateDelta(idx){const i=this.items[idx];i.manhours=(parseFloat(i.cumulative_mh||0)-i.previous_mh).toFixed(2)},
        remove(idx){if(confirm('Remove this entry?'))this.items.splice(idx,1)},
        formatNumber(n){return new Intl.NumberFormat('en-US',{minimumFractionDigits:2,maximumFractionDigits:2}).format(n)}
    }
}
</script>
@endsection