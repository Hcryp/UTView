@extends('layout.AdmLayout')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="mb-6 flex justify-between items-end">
    <div>
        <h2 class="text-2xl font-bold text-slate-800 tracking-tight">EHS & Operational Performance</h2>
        <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">As of October 2025 | PT United Tractors</p>
    </div>
    <div class="flex gap-2">
        <span class="px-3 py-1 bg-[#002d5b] text-white text-[10px] font-bold rounded shadow">SATUI</span>
        <span class="px-3 py-1 bg-yellow-400 text-black text-[10px] font-bold rounded shadow">BATULICIN</span>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded shadow-sm p-5 border-l-4 border-[#002d5b] flex justify-between items-center relative overflow-hidden">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $data['manhours']['satui']['label'] }}</p>
            <h3 class="text-4xl font-extrabold text-slate-800 mt-1">{{ $data['manhours']['satui']['value'] }} <span class="text-sm text-gray-400 font-normal">Jam</span></h3>
        </div>
        <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-blue-50 to-transparent opacity-50"></div>
    </div>
    <div class="bg-white rounded shadow-sm p-5 border-l-4 border-yellow-400 flex justify-between items-center relative overflow-hidden">
        <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $data['manhours']['batu']['label'] }}</p>
            <h3 class="text-4xl font-extrabold text-slate-800 mt-1">{{ $data['manhours']['batu']['value'] }} <span class="text-sm text-gray-400 font-normal">Jam</span></h3>
        </div>
        <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-yellow-50 to-transparent opacity-50"></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="lg:col-span-2 bg-white p-5 rounded shadow-sm">
        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h3 class="text-sm font-bold text-slate-700 uppercase">Accident Frequency (2021–2025)</h3>
            <div class="flex gap-2 text-[10px]">
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-[#002d5b]"></span> Satui</span>
                <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-yellow-400"></span> Batulicin</span>
            </div>
        </div>
        <div class="h-60">
            <canvas id="chartAccident"></canvas>
        </div>
    </div>

    <div class="bg-white p-5 rounded shadow-sm">
        <h3 class="text-sm font-bold text-slate-700 uppercase border-b pb-2 mb-4">MCU Status Ratio 2024–2025</h3>
        <div class="h-40 mb-4">
            <canvas id="chartMCU"></canvas>
        </div>
        <div class="grid grid-cols-1 gap-2 text-xs">
            <div class="flex justify-between border-l-2 border-green-500 pl-2">
                <span class="text-gray-600">Fit</span>
                <span class="font-bold">{{ $data['mcu']['fit'] }}%</span>
            </div>
            <div class="flex justify-between border-l-2 border-red-500 pl-2">
                <span class="text-gray-600">Temp Unfit</span>
                <span class="font-bold">{{ $data['mcu']['temp_unfit'] }}%</span>
            </div>
            <div class="flex justify-between border-l-2 border-gray-500 pl-2">
                <span class="text-gray-600">Unfit (Note)</span>
                <span class="font-bold">{{ $data['mcu']['unfit_note'] }}%</span>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-5 rounded shadow-sm">
        <h3 class="text-[10px] font-bold text-gray-400 uppercase mb-2">Manpower: Satui</h3>
        <div class="h-32 mb-2"><canvas id="mpSatui"></canvas></div>
        <div class="text-center text-xs font-bold text-slate-800">Total: {{ array_sum($data['manpower']['satui']) }}</div>
    </div>

    <div class="bg-white p-5 rounded shadow-sm">
        <h3 class="text-[10px] font-bold text-gray-400 uppercase mb-2">Manpower: Batulicin</h3>
        <div class="h-32 mb-2"><canvas id="mpBatu"></canvas></div>
        <div class="text-center text-xs font-bold text-slate-800">Total: {{ array_sum($data['manpower']['batu']) }}</div>
    </div>

    <div class="lg:col-span-2 bg-white p-5 rounded shadow-sm">
        <h3 class="text-sm font-bold text-slate-700 uppercase border-b pb-2 mb-4">Energy & Emission Trend (2025)</h3>
        <div class="grid grid-cols-3 gap-2">
            <div>
                <p class="text-[10px] text-center mb-1 text-gray-500">Electricity (KWh)</p>
                <div class="h-32"><canvas id="enElec"></canvas></div>
            </div>
            <div>
                <p class="text-[10px] text-center mb-1 text-gray-500">Fuel (Liter)</p>
                <div class="h-32"><canvas id="enFuel"></canvas></div>
            </div>
            <div>
                <p class="text-[10px] text-center mb-1 text-gray-500">Water (m³)</p>
                <div class="h-32"><canvas id="enWater"></canvas></div>
            </div>
        </div>
    </div>
</div>

<div class="mb-4">
    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Additional Monitoring</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-white rounded shadow-sm p-5 md:col-span-2 border border-gray-100 relative group hover:border-blue-200 transition-colors cursor-pointer">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-sm font-bold text-slate-700">Projected ESG Scorecard</h3>
                <span class="bg-gray-100 text-gray-500 text-[10px] px-2 py-0.5 rounded">Q4 2025</span>
            </div>
            <div class="h-32 border-2 border-dashed border-gray-200 rounded-lg flex flex-col items-center justify-center text-gray-400 bg-gray-50">
                <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="text-xs">Widget Slot: ESG Analytics</span>
            </div>
        </div>

        <div class="bg-white rounded shadow-sm p-5 border border-gray-100 relative group hover:border-yellow-200 transition-colors cursor-pointer">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-sm font-bold text-slate-700">System Status</h3>
                <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            </div>
            <div class="space-y-3">
                <div class="h-8 bg-gray-50 rounded w-full border border-gray-100"></div>
                <div class="h-8 bg-gray-50 rounded w-full border border-gray-100"></div>
                <div class="h-8 bg-gray-50 rounded w-3/4 border border-gray-100"></div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                <span class="text-[10px] font-bold text-blue-600 uppercase tracking-wide">View Server Logs</span>
            </div>
        </div>

    </div>
</div>


<script>
    const commonOptions = { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } };
    const utBlue = '#002d5b';
    const utYellow = '#facc15';
    const utGray = '#94a3b8';

    // 1. Accidents
    new Chart(document.getElementById('chartAccident'), {
        type: 'line',
        data: {
            labels: @json($data['accidents']['years']),
            datasets: [
                {
                    label: 'Satui',
                    data: @json($data['accidents']['satui']),
                    borderColor: utBlue, backgroundColor: utBlue,
                    tension: 0.1, borderWidth: 2, pointRadius: 4
                },
                {
                    label: 'Batulicin',
                    data: @json($data['accidents']['batu']),
                    borderColor: utYellow, backgroundColor: utYellow,
                    tension: 0.1, borderWidth: 2, pointRadius: 4
                }
            ]
        },
        options: { ...commonOptions, scales: { y: { beginAtZero: true, suggestedMax: 5 } } }
    });

    // 2. MCU Ratio
    new Chart(document.getElementById('chartMCU'), {
        type: 'doughnut',
        data: {
            labels: ['Fit', 'Temp Unfit', 'Unfit Note'],
            datasets: [{
                data: [{{ $data['mcu']['fit'] }}, {{ $data['mcu']['temp_unfit'] }}, {{ $data['mcu']['unfit_note'] }}],
                backgroundColor: ['#22c55e', '#ef4444', '#64748b'],
                borderWidth: 0
            }]
        },
        options: commonOptions
    });

    // 3. Manpower Satui
    new Chart(document.getElementById('mpSatui'), {
        type: 'pie',
        data: {
            labels: ['Partner', 'UT', 'OJT'],
            datasets: [{
                data: [{{ $data['manpower']['satui']['partner'] }}, {{ $data['manpower']['satui']['ut'] }}, {{ $data['manpower']['satui']['ojt'] }}],
                backgroundColor: [utGray, utBlue, utYellow],
                borderWidth: 1
            }]
        },
        options: commonOptions
    });

    // 4. Manpower Batulicin
    new Chart(document.getElementById('mpBatu'), {
        type: 'pie',
        data: {
            labels: ['Partner', 'UT', 'OJT'],
            datasets: [{
                data: [{{ $data['manpower']['batu']['partner'] }}, {{ $data['manpower']['batu']['ut'] }}, {{ $data['manpower']['batu']['ojt'] }}],
                backgroundColor: [utGray, utBlue, utYellow],
                borderWidth: 1
            }]
        },
        options: commonOptions
    });

    // 5. Energy Charts
    const energyLabels = @json($data['energy']['months']);
    
    new Chart(document.getElementById('enElec'), {
        type: 'bar',
        data: { labels: energyLabels, datasets: [{ data: @json($data['energy']['electricity']), backgroundColor: utBlue }] },
        options: { ...commonOptions, scales: { x: { display: false }, y: { display: false } } }
    });

    new Chart(document.getElementById('enFuel'), {
        type: 'bar',
        data: { labels: energyLabels, datasets: [{ data: @json($data['energy']['fuel']), backgroundColor: utYellow }] },
        options: { ...commonOptions, scales: { x: { display: false }, y: { display: false } } }
    });

    new Chart(document.getElementById('enWater'), {
        type: 'line',
        data: { labels: energyLabels, datasets: [{ data: @json($data['energy']['water']), borderColor: '#10b981', borderWidth: 2, pointRadius: 0 }] },
        options: { ...commonOptions, scales: { x: { display: false }, y: { display: false } } }
    });
</script>
@endsection