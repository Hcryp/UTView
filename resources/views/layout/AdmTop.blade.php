<nav class="ut-blue text-white h-16 flex items-center justify-between px-6 shadow-md z-20 relative">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 ut-yellow rounded flex items-center justify-center text-black font-bold text-xs">UT</div>
        <div>
            <h1 class="font-bold text-lg leading-tight tracking-wide">UTVIEW <span class="font-light opacity-80">ADMIN</span></h1>
            <p class="text-[10px] opacity-70 uppercase tracking-widest">United Tractors Satui</p>
        </div>
    </div>

    <div class="flex items-center gap-4">
        <div class="text-right hidden md:block">
            <p class="font-bold text-xs">{{ Auth::user()->username ?? 'Guest' }}</p>
            <p class="text-[10px] opacity-70">Administrator</p>
        </div>
        <div class="w-8 h-8 bg-gray-600 rounded-full border-2 border-gray-400"></div>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-xs font-bold py-1 px-3 rounded transition">LOGOUT</button>
        </form>
    </div>
</nav>