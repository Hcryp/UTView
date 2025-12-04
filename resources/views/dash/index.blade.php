@extends('layout.AdmLayout')
@section('content')

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Dashboard K3 & ESG</h2>
        <p class="text-xs text-gray-500 mt-1">Real-time monitoring and performance indicators.</p>
    </div>
    <button class="bg-[#002d5b] text-white text-xs px-4 py-2 rounded shadow hover:bg-blue-900">Download Report</button>
</div>

<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded shadow-sm p-4 border-t-4 border-yellow-400">
        <h3 class="text-gray-400 text-[10px] font-bold uppercase">Total Manhours</h3>
        <p class="text-2xl font-bold text-slate-800 mt-1">245,092</p>
        <p class="text-[10px] text-green-600 font-bold mt-1">▲ 2.5% vs Last Month</p>
    </div>
    <div class="bg-white rounded shadow-sm p-4 border-t-4 border-blue-600">
        <h3 class="text-gray-400 text-[10px] font-bold uppercase">Safe Manhours</h3>
        <p class="text-2xl font-bold text-slate-800 mt-1">1,240,110</p>
    </div>
    <div class="bg-white rounded shadow-sm p-4 border-t-4 border-red-500">
        <h3 class="text-gray-400 text-[10px] font-bold uppercase">Accidents (LTI)</h3>
        <p class="text-2xl font-bold text-slate-800 mt-1">0</p>
    </div>
    <div class="bg-white rounded shadow-sm p-4 border-t-4 border-green-500">
        <h3 class="text-gray-400 text-[10px] font-bold uppercase">Compliance</h3>
        <p class="text-2xl font-bold text-slate-800 mt-1">100%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2 bg-white p-6 rounded shadow-sm">
        <h3 class="font-bold text-gray-700 border-b pb-2 mb-4 text-sm">Monthly Trends</h3>
        <div class="h-64 bg-gray-50 flex items-center justify-center text-gray-400 text-xs border border-dashed border-gray-300">
            [Chart Visualization Placeholder]
        </div>
    </div>
    
    <div class="bg-white p-6 rounded shadow-sm">
        <h3 class="font-bold text-gray-700 border-b pb-2 mb-4 text-sm">Recent Activities</h3>
        <ul class="space-y-3">
            <li class="text-xs flex justify-between"><span class="text-gray-600">Site Inspection</span> <span class="text-green-600 font-bold">Done</span></li>
            <li class="text-xs flex justify-between"><span class="text-gray-600">Safety Talk</span> <span class="text-blue-600 font-bold">Pending</span></li>
            <li class="text-xs flex justify-between"><span class="text-gray-600">APAR Check</span> <span class="text-green-600 font-bold">Done</span></li>
        </ul>
    </div>
</div>
@endsection