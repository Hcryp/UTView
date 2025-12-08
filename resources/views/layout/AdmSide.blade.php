<aside class="w-60 bg-white border-r border-gray-300 flex-shrink-0 flex flex-col justify-between">
    <div class="py-4">
        <div class="px-6 mb-2 mt-2 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Dashboard</div>
        <a href="{{ route('dash.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-50 {{ request()->routeIs('dash.index') ? 'border-r-4 border-[#002d5b] bg-blue-50 text-[#002d5b] font-bold' : 'text-gray-600' }}">
            <span>Overview</span>
        </a>

        <div class="px-6 mb-2 mt-6 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Data Manager (K3 & ESG)</div>
        <a href="{{ route('dash.data') }}" class="flex items-center px-6 py-3 hover:bg-gray-50 {{ request()->routeIs('dash.data') ? 'border-r-4 border-[#002d5b] bg-blue-50 text-[#002d5b] font-bold' : 'text-gray-600' }}">
            <span>Analytics Input</span>
        </a>
        <a href="{{ route('manpower.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-50 text-gray-600 {{ request()->routeIs('manpower*') ? 'border-r-4 border-[#002d5b] bg-blue-50 text-[#002d5b] font-bold' : 'text-gray-600' }}">
            <span>Manpower List</span>
        </a>

        <div class="px-6 mb-2 mt-6 text-[10px] font-bold text-gray-400 uppercase tracking-wider">Wiki Manager</div>
        <a href="{{ route('dash.wiki') }}" class="flex items-center px-6 py-3 hover:bg-gray-50 {{ request()->routeIs('dash.wiki') ? 'border-r-4 border-[#002d5b] bg-blue-50 text-[#002d5b] font-bold' : 'text-gray-600' }}">
            <span>Manage Content</span>
        </a>
    </div>

    <div class="p-4 border-t border-gray-200">
        <p class="text-[10px] text-center text-gray-400">Ver 1.0 | UT Satui</p>
    </div>
</aside>