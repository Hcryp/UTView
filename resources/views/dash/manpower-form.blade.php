@extends('layout.AdmLayout')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('manpower.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#002d5b] uppercase tracking-wide">&larr; Back to List</a>
        <h2 class="text-2xl font-extrabold text-slate-800 mt-2">{{ $title }}</h2>
    </div>

    <form x-data="{ status: '{{ old('status', $mp->status ?? 'ACTIVE') }}' }" 
          action="{{ $mp->exists ? route('manpower.update', $mp->id) : route('manpower.store') }}" 
          method="POST" 
          class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        
        @csrf @if($mp->exists) @method('PUT') @endif

        <div class="p-6 border-b border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="col-span-2 md:col-span-1">
                <label class="block text-xs font-bold text-slate-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $mp->name) }}" class="w-full text-sm border-slate-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500" required>
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="col-span-2 md:col-span-1">
                <label class="block text-xs font-bold text-slate-700 mb-1">NRP / Employee ID</label>
                <input type="text" name="nrp" value="{{ old('nrp', $mp->nrp) }}" class="w-full text-sm border-slate-300 rounded focus:border-blue-500 focus:ring-1 focus:ring-blue-500">
            </div>
        </div>

        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xs font-bold text-[#002d5b] uppercase tracking-wider mb-4 border-b border-slate-200 pb-2">Employment Data</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Site <span class="text-red-500">*</span></label>
                    <select name="site" class="w-full text-sm border-slate-300 rounded bg-white" required>
                        <option value="SATUI" {{ old('site', $mp->site) == 'SATUI' ? 'selected' : '' }}>SATUI</option>
                        <option value="BATULICIN" {{ old('site', $mp->site) == 'BATULICIN' ? 'selected' : '' }}>BATULICIN</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Category <span class="text-red-500">*</span></label>
                    <select name="category" class="w-full text-sm border-slate-300 rounded bg-white" required>
                        <option value="" disabled selected>Select Category...</option>
                        @foreach($opt_categories as $cat)
                            <option value="{{ $cat }}" {{ old('category', $mp->category) == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Company Name <span class="text-red-500">*</span></label>
                    <input type="text" name="company" value="{{ old('company', $mp->company) }}" class="w-full text-sm border-slate-300 rounded" placeholder="e.g. PT UNITED TRACTORS" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Job Title (Jabatan)</label>
                    <input type="text" name="role" value="{{ old('role', $mp->role) }}" class="w-full text-sm border-slate-300 rounded">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Department</label>
                    <select name="department" class="w-full text-sm border-slate-300 rounded bg-white">
                        <option value="" selected>-- No Department --</option>
                        @foreach($opt_depts as $dept)
                            <option value="{{ $dept }}" {{ old('department', $mp->department) == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Join Date</label>
                <input type="date" name="join_date" value="{{ old('join_date', $mp->join_date) }}" class="w-full text-sm border-slate-300 rounded text-slate-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">End Contract Date</label>
                <input type="date" name="end_date" value="{{ old('end_date', $mp->end_date) }}" class="w-full text-sm border-slate-300 rounded text-slate-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Current Manhours</label>
                <input type="number" step="0.01" name="manhours" value="{{ old('manhours', $mp->manhours ?? 0) }}" class="w-full text-sm border-slate-300 rounded text-right font-mono">
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
                        <input type="date" name="date_out" value="{{ old('date_out', $mp->date_out) }}" class="w-full text-sm border-red-200 rounded text-slate-600 focus:border-red-500 focus:ring-red-200">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 mb-1">Reason (Keterangan)</label>
                        <select name="out_reason" class="w-full text-sm border-red-200 rounded text-slate-600 focus:border-red-500 focus:ring-red-200">
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