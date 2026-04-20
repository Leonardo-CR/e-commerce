<div
    x-data="{ open: false }"
    x-on:show-logout-modal.window="open = true"
    x-show="open"
    class="fixed inset-0 z-[100] overflow-y-auto"
    style="display: none;"
>
    <!-- Overlay -->
    <div 
        x-show="open"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"
        @click="open = false"
    ></div>

    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div 
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="relative w-full max-w-sm overflow-hidden rounded-[2.5rem] bg-white p-8 shadow-2xl border border-slate-100"
        >
            <!-- Illustration -->
            <div class="mb-6 flex justify-center">
                <div class="w-16 h-16 bg-red-50 rounded-full flex items-center justify-center">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </div>
            </div>

            <h3 class="text-xl font-extrabold text-slate-900 text-center mb-2 tracking-tight">
                ¿Realmente quieres salir?
            </h3>
            
            <p class="text-slate-500 text-center mb-8 leading-relaxed px-4 text-sm">
                Tu sesión se cerrará y tendrás que volver a ingresar para gestionar tus pedidos.
            </p>

            <div class="flex flex-col space-y-3">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex w-full items-center justify-center rounded-full bg-red-500 py-3.5 text-sm font-bold text-white shadow-xl shadow-red-100 hover:bg-red-600 transition transform active:scale-95">
                        Sí, cerrar sesión
                    </button>
                </form>
                
                <button @click="open = false" class="flex w-full items-center justify-center rounded-full bg-slate-50 py-3.5 text-sm font-bold text-slate-600 hover:bg-slate-100 transition">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>
