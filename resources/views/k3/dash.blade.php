@extends('layout.base')
@section('title', '| K3 Analytics')
@section('content')
<header class="flex justify-between items-center mb-8">
    <div><h1 class="text-2xl font-bold">K3 & ESG Dashboard</h1><p class="text-sm text-gray-500">Internal Analysis Only</p></div>
    <div class="bg-yellow-400 text-black px-4 py-1 rounded text-xs font-bold">ADMIN VIEW</div>
</header>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded shadow-sm border border-gray-100">
        <h3 class="text-gray-400 text-xs uppercase font-semibold">Total Manhours</h3>
        <p class="text-3xl font-bold mt-2">0</p>
    </div>
    <div class="bg-white p-6 rounded shadow-sm border border-gray-100">
        <h3 class="text-gray-400 text-xs uppercase font-semibold">Safety Index</h3>
        <p class="text-3xl font-bold mt-2 text-green-600">A</p>
    </div>
    <div class="bg-white p-6 rounded shadow-sm border border-gray-100">
        <h3 class="text-gray-400 text-xs uppercase font-semibold">Incidents</h3>
        <p class="text-3xl font-bold mt-2">0</p>
    </div>
</div>
@endsection