@extends('layout.AdmLayout')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('manpower.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#002d5b] uppercase tracking-wide">&larr; Back to List</a>
        <h2 class="text-2xl font-extrabold text-slate-800 mt-2">{{ $title }}</h2>
    </div>

    <form x-data="{ 
            status: @js(old('status', $mp->status ?? 'ACTIVE')),
            init() {
                this.$watch('status', value => {
                    if (value === 'ACTIVE') {
                        if(this.$refs.date_out) this.$refs.date_out.value = '';
                        if(this.$refs.out_reason) this.$refs.out_reason.value = '';
                    }
                })
            }
          }" 
          action="{{ $mp->exists ? route('manpower.update', $mp->id) : route('manpower.store') }}" 
          method="POST" 
          class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        @csrf @if($mp->exists) @method('PUT') @endif

        <div class="p-6 border-b border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-2 md:col-span-1">
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $mp->name) }}" class="w-full text-sm border-slate-300 bg-slate-100 rounded focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors shadow-sm" required>
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            
            <div class="col-span-2 md:col-span-1"
                 x-data="{
                    nrp: @js(old('nrp', $mp->nrp)),
                    checking: false,
                    isTaken: false,
                    placeholders: ['-', 'no id', 'n/a', 'none', 'unknown', 'no_id', 'no-id'],
                    currentId: @js($mp->id),
                    check() {
                        let val = (this.nrp || '').trim().toLowerCase();
                        if (val.length < 1 || this.placeholders.includes(val)) { 
                            this.isTaken = false; 
                            return; 
                        }
                        
                        this.checking = true;
                        let url = '{{ route('manpower.check-nrp') }}?nrp=' + encodeURIComponent(this.nrp);
                        if (this.currentId) {
                            url += '&ignore_id=' + this.currentId;
                        }

                        fetch(url)
                            .then(res => res.json())
                            .then(data => {
                                this.isTaken = data.exists;
                                this.checking = false;
                            })
                            .catch(() => this.checking = false);
                    }
                 }">
                <label class="block text-xs font-bold text-slate-700 mb-1">NRP / Employee ID</label>
                <div class="relative">
                    <input type="text" 
                           name="nrp" 
                           x-model="nrp"
                           @input.debounce.800ms="check()"
                           class="w-full text-sm border-slate-300 bg-slate-100 rounded focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors shadow-sm"
                           :class="{'border-red-500 focus:border-red-500 focus:ring-red-500': isTaken, 'border-green-500': !isTaken && nrp && !checking && !placeholders.includes((nrp || '').toLowerCase())}">
                    
                    <div x-show="checking" class="absolute right-3 top-2.5 text-slate-400">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <div x-show="!checking && isTaken" class="absolute right-3 top-2.5 text-red-500" title="NRP already exists">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                </div>
                <p x-show="isTaken && !checking" class="text-xs text-red-500 mt-1">This NRP is already registered to another employee.</p>
                @error('nrp') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xs font-bold text-[#002d5b] uppercase tracking-wider mb-4 border-b border-slate-200 pb-2">Employment Data</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div x-data="{
                    open: false,
                    search: @js(old('site', $mp->site)),
                    options: @js($opt_sites),
                    filteredOptions() {
                        return this.options.filter(i => i.toLowerCase().includes((this.search || '').toLowerCase()))
                    },
                    select(val) {
                        this.search = val;
                        this.open = false;
                    }
                 }"
                 @click.outside="open = false">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Site <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="site" x-ref="input" x-model="search" @focus="open = true" @input="open = true"
                               class="w-full text-sm border-slate-300 bg-slate-100 rounded placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 pr-16 transition-colors shadow-sm" 
                               placeholder="Select Site..." autocomplete="off" required>
                        <button type="button" x-show="search && search.length > 0" @click="search = ''; open = true; $nextTick(() => $refs.input.focus())"
                                class="absolute inset-y-0 right-7 flex items-center justify-center w-6 h-full text-slate-300 hover:text-red-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <div x-show="open && filteredOptions().length > 0" 
                             class="absolute z-10 w-full bg-white mt-1 border border-slate-200 rounded shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                            <template x-for="option in filteredOptions()" :key="option">
                                <div @click="select(option)" class="px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 cursor-pointer transition-colors" x-text="option"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div x-data="{
                    open: false,
                    search: @js(old('category', $mp->category)),
                    options: @js($opt_categories),
                    filteredOptions() {
                        return this.options.filter(i => i.toLowerCase().includes((this.search || '').toLowerCase()))
                    },
                    select(val) {
                        this.search = val;
                        this.open = false;
                    }
                 }"
                 @click.outside="open = false">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Category <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="category" x-ref="input" x-model="search" @focus="open = true" @input="open = true"
                               class="w-full text-sm border-slate-300 bg-slate-100 rounded placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 pr-16 transition-colors shadow-sm" 
                               placeholder="Select Category..." autocomplete="off" required>
                        <button type="button" x-show="search && search.length > 0" @click="search = ''; open = true; $nextTick(() => $refs.input.focus())"
                                class="absolute inset-y-0 right-7 flex items-center justify-center w-6 h-full text-slate-300 hover:text-red-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <div x-show="open && filteredOptions().length > 0" 
                             class="absolute z-10 w-full bg-white mt-1 border border-slate-200 rounded shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                            <template x-for="option in filteredOptions()" :key="option">
                                <div @click="select(option)" class="px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 cursor-pointer transition-colors" x-text="option"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2" 
                     x-data="{
                        open: false,
                        search: @js(old('company', $mp->company)),
                        options: @js($existing_companies),
                        filteredOptions() {
                            return this.options.filter(i => i.toLowerCase().includes((this.search || '').toLowerCase()))
                        },
                        select(val) {
                            this.search = val;
                            this.open = false;
                        }
                     }"
                     @click.outside="open = false">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Company Name <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="text" name="company" x-ref="input" x-model="search" @focus="open = true" @input="open = true"
                               class="w-full text-sm border-slate-300 bg-slate-100 rounded placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 pr-16 transition-colors shadow-sm" 
                               placeholder="Type to search existing or enter new..." autocomplete="off" required>
                        <button type="button" x-show="search && search.length > 0" @click="search = ''; open = true; $nextTick(() => $refs.input.focus())"
                                class="absolute inset-y-0 right-7 flex items-center justify-center w-6 h-full text-slate-300 hover:text-red-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <div x-show="open && filteredOptions().length > 0" 
                             class="absolute z-10 w-full bg-white mt-1 border border-slate-200 rounded shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                            <template x-for="option in filteredOptions()" :key="option">
                                <div @click="select(option)" class="px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 cursor-pointer transition-colors" x-text="option"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div x-data="{
                        open: false,
                        search: @js(old('role', $mp->role)),
                        options: @js($existing_roles),
                        filteredOptions() {
                            return this.options.filter(i => i.toLowerCase().includes((this.search || '').toLowerCase()))
                        },
                        select(val) {
                            this.search = val;
                            this.open = false;
                        }
                     }"
                     @click.outside="open = false">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Job Title (Jabatan)</label>
                    <div class="relative">
                        <input type="text" name="role" x-ref="input" x-model="search" @focus="open = true" @input="open = true"
                               class="w-full text-sm border-slate-300 bg-slate-100 rounded placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 pr-16 transition-colors shadow-sm" 
                               placeholder="e.g. Mechanic II" autocomplete="off">
                        <button type="button" x-show="search && search.length > 0" @click="search = ''; open = true; $nextTick(() => $refs.input.focus())"
                                class="absolute inset-y-0 right-7 flex items-center justify-center w-6 h-full text-slate-300 hover:text-red-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <div x-show="open && filteredOptions().length > 0" 
                             class="absolute z-10 w-full bg-white mt-1 border border-slate-200 rounded shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                            <template x-for="option in filteredOptions()" :key="option">
                                <div @click="select(option)" class="px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 cursor-pointer transition-colors" x-text="option"></div>
                            </template>
                        </div>
                    </div>
                </div>

                <div x-data="{
                        open: false,
                        search: @js(old('department', $mp->department)),
                        options: @js($opt_depts),
                        filteredOptions() {
                            return this.options.filter(i => i.toLowerCase().includes((this.search || '').toLowerCase()))
                        },
                        select(val) {
                            this.search = val;
                            this.open = false;
                        }
                     }"
                     @click.outside="open = false">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Department</label>
                    <div class="relative">
                        <input type="text" name="department" x-ref="input" x-model="search" @focus="open = true" @input="open = true"
                               class="w-full text-sm border-slate-300 bg-slate-100 rounded placeholder:text-slate-400 focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 pr-16 transition-colors shadow-sm" 
                               placeholder="Select Department..." autocomplete="off">
                        <button type="button" x-show="search && search.length > 0" @click="search = ''; open = true; $nextTick(() => $refs.input.focus())"
                                class="absolute inset-y-0 right-7 flex items-center justify-center w-6 h-full text-slate-300 hover:text-red-500 transition-colors">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                        <div x-show="open && filteredOptions().length > 0" 
                             class="absolute z-10 w-full bg-white mt-1 border border-slate-200 rounded shadow-lg max-h-60 overflow-y-auto" style="display: none;">
                            <template x-for="option in filteredOptions()" :key="option">
                                <div @click="select(option)" class="px-4 py-2 text-sm text-slate-700 hover:bg-blue-50 cursor-pointer transition-colors" x-text="option"></div>
                            </template>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Join Date</label>
                <input type="date" name="join_date" value="{{ old('join_date', $mp->join_date) }}" class="w-full text-sm border-slate-300 bg-slate-100 rounded focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors text-slate-600 shadow-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">End Contract Date</label>
                <input type="date" name="end_date" value="{{ old('end_date', $mp->end_date) }}" class="w-full text-sm border-slate-300 bg-slate-100 rounded focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors text-slate-600 shadow-sm">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Current Manhours</label>
                <input type="number" step="0.01" name="manhours" value="{{ old('manhours', $mp->manhours ?? 0) }}" class="w-full text-sm border-slate-300 bg-slate-100 rounded focus:bg-white focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-colors text-right font-mono shadow-sm">
            </div>
        </div>

        <div class="bg-gray-100 p-6 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                
                <div>
                    <label class="block text-xs font-bold text-slate-600 uppercase mb-2">Current Status</label>
                    <div class="flex items-center gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="ACTIVE" x-model="status" class="text-blue-600 focus:ring-blue-500 border-gray-300">
                            <span class="text-sm font-bold text-green-700">ACTIVE</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="status" value="INACTIVE" x-model="status" class="text-red-600 focus:ring-red-500 border-gray-300">
                            <span class="text-sm font-bold text-red-700">INACTIVE</span>
                        </label>
                    </div>
                </div>

                <div x-show="status === 'INACTIVE'" x-transition class="md:col-span-2 grid grid-cols-2 gap-4 bg-white p-4 rounded border border-red-200">
                    <div class="col-span-2">
                        <p class="text-[10px] font-bold text-red-400 uppercase tracking-wider mb-2">Separation Details</p>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Date Out</label>
                        <input type="date" name="date_out" x-ref="date_out" value="{{ old('date_out', $mp->date_out) }}" class="w-full text-sm border-red-200 bg-red-50 rounded text-slate-600 focus:bg-white focus:border-red-500 focus:ring-red-200 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Reason (Keterangan)</label>
                        <select name="out_reason" x-ref="out_reason" class="w-full text-sm border-red-200 bg-red-50 rounded text-slate-600 focus:bg-white focus:border-red-500 focus:ring-red-200 transition-colors">
                            <option value="">-- Select Reason --</option>
                            @foreach($opt_out_reasons as $reason)
                                <option value="{{ $reason }}" {{ old('out_reason', $mp->out_reason) == $reason ? 'selected' : '' }}>{{ $reason }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('manpower.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 font-bold rounded text-xs hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-[#002d5b] text-white font-bold rounded text-xs hover:bg-blue-900 shadow-md transition">Save Changes</button>
            </div>
        </div>
    </form>
</div>
<script src="//unpkg.com/alpinejs" defer></script>
@endsection