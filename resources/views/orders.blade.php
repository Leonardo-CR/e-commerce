<x-halosound-layout>
    <x-slot name="title">Mis Órdenes | HaloSound</x-slot>

    <section class="max-w-4xl mx-auto py-12">
        <div class="mb-10">
            <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight mb-2">Mis Órdenes</h1>
            <p class="text-slate-500 font-medium">Historial de todos tus pedidos en HaloSound.</p>
        </div>

        @livewire('my-orders')
    </section>
</x-halosound-layout>
