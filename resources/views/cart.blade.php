<x-halosound-layout>
    <x-slot name="title">Tu Carrito | HaloSound</x-slot>

    <div class="max-w-7xl mx-auto py-12">
        <div class="mb-12 border-b border-slate-100 pb-8 flex justify-between items-end">
            <div>
                <nav class="flex mb-4 text-sm font-medium text-slate-400 space-x-2">
                    <a href="/" class="hover:text-indigo-600">Inicio</a>
                    <span>/</span>
                    <a href="/headphones" class="hover:text-indigo-600">Tienda</a>
                    <span>/</span>
                    <span class="text-slate-900">Carrito</span>
                </nav>
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Tu Carrito</h1>
            </div>
            <p class="text-slate-500 font-medium pb-1">
                {{ auth()->user()->cart?->cartItems->sum('quantity') ?? 0 }} artículos seleccionados
            </p>
        </div>

        @livewire('cart-detail')
    </div>
</x-halosound-layout>
