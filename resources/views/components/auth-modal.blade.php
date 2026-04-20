<div
    x-data="{ open: false }"
    x-on:show-auth-modal.window="open = true"
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
            class="relative w-full max-w-md overflow-hidden rounded-[2.5rem] bg-white p-8 shadow-2xl border border-slate-100"
        >
            <!-- Close Button -->
            <button @click="open = false" class="absolute right-6 top-6 text-slate-400 hover:text-slate-900 transition font-bold text-xl">
                &times;
            </button>

            <!-- Illustration -->
            <div class="mb-8 flex justify-center">
                <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>

            <h3 class="text-2xl font-extrabold text-slate-900 text-center mb-4 tracking-tight">
                ¡Únete a la experiencia HaloSound!
            </h3>
            
            <p class="text-slate-500 text-center mb-10 leading-relaxed px-4">
                Para añadir productos a tu carrito y disfrutar de una experiencia sonora personalizada, necesitas una cuenta.
            </p>

            <div class="space-y-4">
                <a href="{{ route('register') }}" class="flex w-full items-center justify-center rounded-full bg-indigo-600 py-4 text-sm font-bold text-white shadow-xl shadow-indigo-200 hover:bg-indigo-700 transition transform active:scale-95">
                    Crear una cuenta gratis
                </a>
                
                <a href="{{ route('login') }}" class="flex w-full items-center justify-center rounded-full border border-slate-200 bg-white py-4 text-sm font-bold text-slate-700 hover:border-slate-900 transition">
                    Ya tengo cuenta, iniciar sesión
                </a>

                <button @click="open = false" class="w-full text-sm font-medium text-slate-400 hover:text-slate-600 py-2 transition">
                    Seguir explorando
                </button>
            </div>
            
            <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-center space-x-2 grayscale opacity-40">
                <img src="{{ asset('images/image 3@2x.png') }}" alt="HaloSound" class="h-6">
            </div>
        </div>
    </div>
</div>
