<div class="py-12">
    <div class="flex flex-col lg:flex-row gap-12">
        <!-- Items List -->
        <div class="flex-1 space-y-6">
            @forelse($items as $item)
                <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-50 flex items-center gap-6 group hover:shadow-md transition">
                    <!-- Image -->
                    <div class="w-24 h-24 bg-slate-50 rounded-2xl flex items-center justify-center p-4 overflow-hidden">
                        <img 
                            src="{{ filter_var($item->earphone->image, FILTER_VALIDATE_URL) ? $item->earphone->image : Storage::url($item->earphone->image) }}" 
                            alt="{{ $item->earphone->name }}" 
                            class="w-full h-full object-contain"
                        >
                    </div>

                    <!-- Details -->
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $item->earphone->name }}</h3>
                        <p class="text-sm text-slate-500 mb-4 truncate max-w-xs">{{ $item->earphone->description }}</p>
                        
                        <div class="flex items-center gap-4">
                            <div class="flex items-center bg-slate-50 rounded-lg p-1 border border-slate-100">
                                <button wire:click="updateQuantity({{ $item->idCart_Item }}, {{ $item->quantity - 1 }})" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition">-</button>
                                <span class="w-10 text-center font-bold text-slate-700">{{ $item->quantity }}</span>
                                <button wire:click="updateQuantity({{ $item->idCart_Item }}, {{ $item->quantity + 1 }})" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition">+</button>
                            </div>
                            <button wire:click="removeItem({{ $item->idCart_Item }})" class="text-xs font-bold text-red-400 hover:text-red-600 transition uppercase tracking-wider">
                                Eliminar
                            </button>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="text-right">
                        <p class="text-sm text-slate-400 mb-2">${{ number_format($item->unit_price, 2) }} c/u</p>
                        <p class="text-xl font-black text-slate-900">${{ number_format($item->subtotal, 2) }}</p>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-[3rem] py-20 text-center border border-slate-100 shadow-sm">
                    <div class="mb-6 flex justify-center opacity-10">
                        <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-2">Tu carrito está vacío</h3>
                    <p class="text-slate-500 mb-8">Parece que aún no has elegido tu próximo sonido.</p>
                    <a href="/headphones" class="inline-flex px-8 py-3 bg-indigo-600 text-white rounded-full font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                        Volver a la tienda
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Summary -->
        @if($items->count() > 0)
            <div class="w-full lg:w-96">
                <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-100 border border-slate-50 sticky top-24">
                    <h3 class="text-xl font-bold text-slate-900 mb-8">Resumen de compra</h3>
                    
                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-slate-500">
                            <span>Subtotal</span>
                            <span class="font-bold text-slate-700">${{ number_format($total, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-slate-500">
                            <span>Envío</span>
                            <span class="text-green-500 font-bold">Gratis</span>
                        </div>
                    </div>
                    
                    <div class="pt-6 border-t border-slate-50 mb-8">
                        <div class="flex justify-between items-end">
                            <span class="text-slate-900 font-bold">Total</span>
                            <span class="text-3xl font-black text-indigo-600">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>

                    <button class="w-full py-5 bg-indigo-600 text-white rounded-[1.5rem] font-bold shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition transform active:scale-95 text-lg">
                        Proceder al pago
                    </button>
                    
                    <p class="text-center text-xs text-slate-400 mt-6 px-4">
                        IVA incluido. Envío protegido por HaloSound Security.
                    </p>
                </div>
            </div>
        @endif
    </div>
</div>
