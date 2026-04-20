<a href="/cart" class="relative group p-2 text-slate-600 hover:text-indigo-600 transition">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
    @if($count > 0)
        <span class="absolute top-0 right-0 block h-4 w-4 rounded-full bg-indigo-600 text-[10px] font-bold text-white flex items-center justify-center shadow-sm">
            {{ $count }}
        </span>
    @endif
</a>
