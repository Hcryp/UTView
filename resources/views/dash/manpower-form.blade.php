@extends('layout.AdmLayout')
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('manpower.index') }}" class="text-xs font-bold text-slate-500 hover:text-[#002d5b] uppercase tracking-wide">&larr; Back to List</a>
        <h2 class="text-2xl font-extrabold text-slate-800 mt-2">{{ $title }}</h2>
    </div>

    <form action="{{ $mp->exists ? route('manpower.update', $mp->id) : route('manpower.store') }}" method="POST" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
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
                    <label class="block text-xs font-bold text-slate-500 mb-1">Site</label>
                    <select name="site" class="w-full text-sm border-slate-300 rounded bg-white">
                        <option value="SATUI" {{ old('site', $mp->site) == 'SATUI' ? 'selected' : '' }}>SATUI</option>
                        <option value="BATULICIN" {{ old('site', $mp->site) == 'BATULICIN' ? 'selected' : '' }}>BATULICIN</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Category</label>
                    <select name="category" class="w-full text-sm border-slate-300 rounded bg-white">
                        <option value="KARYAWAN" {{ old('category', $mp->category) == 'KARYAWAN' ? 'selected' : '' }}>KARYAWAN</option>
                        <option value="KONTRAKTOR" {{ old('category', $mp->category) == 'KONTRAKTOR' ? 'selected' : '' }}>KONTRAKTOR</option>
                        <option value="MAGANG" {{ old('category', $mp->category) == 'MAGANG' ? 'selected' : '' }}>MAGANG</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-500 mb-1">Company Name</label>
                    <input type="text" name="company" value="{{ old('company', $mp->company) }}" class="w-full text-sm border-slate-300 rounded" required>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Job Title (Jabatan)</label>
                    <input type="text" name="role" value="{{ old('role', $mp->role) }}" class="w-full text-sm border-slate-300 rounded">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 mb-1">Department</label>
                    <input type="text" name="department" value="{{ old('department', $mp->department) }}" class="w-full text-sm border-slate-300 rounded">
                </div>
            </div>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Join Date</label>
                <input type="date" name="join_date" value="{{ old('join_date', $mp->join_date) }}" class="w-full text-sm border-slate-300 rounded text-slate-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">End Date</label>
                <input type="date" name="end_date" value="{{ old('end_date', $mp->end_date) }}" class="w-full text-sm border-slate-300 rounded text-slate-600">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 mb-1">Current Manhours</label>
                <input type="number" step="0.01" name="manhours" value="{{ old('manhours', $mp->manhours ?? 0) }}" class="w-full text-sm border-slate-300 rounded text-right font-mono">
            </div>
        </div>

        <div class="bg-gray-100 p-4 flex justify-between items-center border-t border-gray-200">
            <div class="flex items-center gap-2">
                <label class="text-xs font-bold text-slate-600 uppercase">Status:</label>
                <select name="status" class="text-xs font-bold border-gray-300 rounded bg-white py-1 pl-2 pr-8 focus:ring-0">
                    <option value="ACTIVE" {{ old('status', $mp->status) == 'ACTIVE' ? 'selected' : '' }}>ACTIVE</option>
                    <option value="RESIGN" {{ old('status', $mp->status) == 'RESIGN' ? 'selected' : '' }}>RESIGN</option>
                    <option value="MUTASI" {{ old('status', $mp->status) == 'MUTASI' ? 'selected' : '' }}>MUTASI</option>
                </select>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('manpower.index') }}" class="px-4 py-2 bg-white border border-gray-300 text-gray-600 font-bold rounded text-xs hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-[#002d5b] text-white font-bold rounded text-xs hover:bg-blue-900 shadow-md transition">Save Changes</button>
            </div>
        </div>
    </form>
</div>
@endsection