<x-halosound-layout>
    <x-slot name="title">Pago Fallido | HaloSound</x-slot>

    <div class="max-w-4xl mx-auto py-24 px-6">
        <div class="bg-white rounded-[3rem] p-12 text-center border border-slate-100 shadow-sm relative overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-red-50 rounded-full blur-3xl opacity-60"></div>
            <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-slate-50 rounded-full blur-3xl opacity-60"></div>
            
            <div class="relative z-10">
                <div class="w-24 h-24 bg-red-100 text-red-500 rounded-[2rem] flex items-center justify-center mx-auto mb-8 shadow-inner">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                
                <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">
                    Ups, algo salió mal
                </h1>
                
                <p class="text-lg text-slate-500 mb-10 max-w-lg mx-auto">
                    No pudimos procesar tu pago. Esto puede deberse a fondos insuficientes, datos incorrectos o tu banco rechazó la transacción. Por favor, intenta nuevamente.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="/cart" class="px-8 py-4 bg-slate-900 text-white rounded-full font-bold shadow-lg shadow-slate-200 hover:bg-slate-800 transition transform active:scale-95 w-full sm:w-auto">
                        Volver al Carrito
                    </a>
                    <a href="/headphones" class="px-8 py-4 bg-white text-slate-600 border border-slate-200 rounded-full font-bold hover:bg-slate-50 transition w-full sm:w-auto">
                        Regresar a la Tienda
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-halosound-layout>
