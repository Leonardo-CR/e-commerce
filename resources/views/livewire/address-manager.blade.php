<div class="py-12">
    <div class="max-w-7xl mx-auto px-6">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div>
                <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-4">Mis Direcciones</h2>
                <p class="text-slate-500">Gestiona tus lugares de entrega para compras más rápidas.</p>
            </div>
            <button 
                wire:click="toggleForm"
                class="inline-flex items-center space-x-2 bg-indigo-600 text-white px-6 py-3 rounded-2xl font-bold hover:scale-105 transition-all shadow-xl shadow-indigo-100"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>{{ $showForm ? 'Cancelar' : 'Nueva Dirección' }}</span>
            </button>
        </div>

        @if(session()->has('message'))
            <div class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl font-bold flex items-center space-x-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                <span>{{ session('message') }}</span>
            </div>
        @endif

        @if($showForm)
            <div class="mb-12 bg-white rounded-[2.5rem] p-8 md:p-12 border border-slate-100 shadow-sm animate-in fade-in slide-in-from-top-4 duration-500">
                <form wire:submit.prevent="store" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Calle</label>
                        <input type="text" wire:model="street" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-indigo-500 transition text-slate-900 placeholder-slate-400" placeholder="Ej. Av. Insurgentes Sur">
                        @error('street') <span class="text-xs text-red-500 ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Número</label>
                        <input type="text" wire:model="number" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-indigo-500 transition text-slate-900 placeholder-slate-400" placeholder="Ej. 123 Int. 4">
                        @error('number') <span class="text-xs text-red-500 ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Colonia</label>
                        <input type="text" wire:model="colony" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-indigo-500 transition text-slate-900 placeholder-slate-400" placeholder="Ej. Roma Norte">
                        @error('colony') <span class="text-xs text-red-500 ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Ciudad</label>
                        <input type="text" wire:model="city" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-indigo-500 transition text-slate-900 placeholder-slate-400" placeholder="Ej. Ciudad de México">
                        @error('city') <span class="text-xs text-red-500 ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Estado</label>
                        <input type="text" wire:model="state" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-indigo-500 transition text-slate-900 placeholder-slate-400" placeholder="Ej. CDMX">
                        @error('state') <span class="text-xs text-red-500 ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700 ml-1">Código Postal</label>
                        <input type="text" wire:model="zip" class="w-full bg-slate-50 border-none rounded-2xl px-6 py-4 focus:ring-2 focus:ring-indigo-500 transition text-slate-900 placeholder-slate-400" placeholder="Ej. 06700">
                        @error('zip') <span class="text-xs text-red-500 ml-1 italic">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2 pt-4">
                        <button type="submit" class="w-full bg-slate-900 text-white py-5 rounded-2xl font-bold shadow-xl shadow-slate-200 hover:scale-[1.02] transition transform active:scale-95 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Guardar Dirección</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($addresses as $address)
                <div class="group relative bg-white border {{ $address->is_default ? 'border-indigo-200 ring-2 ring-indigo-50' : 'border-slate-100 flex flex-col' }} rounded-[2.5rem] p-8 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="flex justify-between items-start mb-6">
                        <div class="w-12 h-12 bg-slate-50 rounded-2xl flex items-center justify-center text-slate-400 group-hover:text-indigo-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        @if($address->is_default)
                            <span class="bg-indigo-600 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full">Predeterminada</span>
                        @else
                            <button wire:click="setDefault({{ $address->idAddress }})" class="text-[10px] font-bold text-slate-400 hover:text-indigo-600 uppercase tracking-widest transition">Hacer principal</button>
                        @endif
                    </div>

                    <div class="space-y-1 mb-8">
                        <h4 class="text-xl font-bold text-slate-900 mb-2 truncate">{{ $address->street }} #{{ $address->number }}</h4>
                        <p class="text-slate-500 font-medium">{{ $address->colony }}</p>
                        <p class="text-slate-400 text-sm italic">{{ $address->city }}, {{ $address->state }} CP: {{ $address->zip }}</p>
                    </div>

                    <div class="mt-auto pt-6 border-t border-slate-50 flex items-center justify-between">
                        <button class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">Editar</button>
                        <button 
                            wire:click="delete({{ $address->idAddress }})"
                            wire:confirm="¿Estás seguro de eliminar esta dirección?"
                            class="text-sm font-bold text-red-400 hover:text-red-600 transition"
                        >
                            Eliminar
                        </button>
                    </div>
                </div>
            @empty
                <div class="md:col-span-3 text-center py-20 bg-white rounded-[3rem] border border-slate-100 shadow-sm">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">No tienes direcciones guardadas</h3>
                    <p class="text-slate-400 max-w-xs mx-auto">Añade una dirección para que podamos enviarte tus audífonos favoritos.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
