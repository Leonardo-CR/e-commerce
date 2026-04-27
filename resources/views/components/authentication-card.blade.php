<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#fdfcfb]">
    <div>
        {{ $logo }}
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-8 bg-white shadow-xl border border-slate-100 overflow-hidden sm:rounded-[2.5rem]">
        {{ $slot }}
    </div>
</div>
