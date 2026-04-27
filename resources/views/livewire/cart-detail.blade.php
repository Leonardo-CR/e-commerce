<div class="py-12">

    {{-- ── Stepper ──────────────────────────────────────────────────────────── --}}
    @if($items->count() > 0)
    <div class="flex items-center gap-2 mb-10 text-sm font-semibold select-none">
        @php
            $steps = ['cart' => 'Carrito', 'address' => 'Dirección', 'shipping' => 'Envío'];
            $order = array_keys($steps);
            $currentIdx = array_search($step, $order);
        @endphp
        @foreach($steps as $key => $label)
            @php $idx = array_search($key, $order); @endphp
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black
                    {{ $idx < $currentIdx ? 'bg-indigo-600 text-white' : ($idx === $currentIdx ? 'bg-indigo-600 text-white ring-4 ring-indigo-100' : 'bg-slate-100 text-slate-400') }}">
                    @if($idx < $currentIdx)
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    @else
                        {{ $idx + 1 }}
                    @endif
                </span>
                <span class="{{ $idx === $currentIdx ? 'text-slate-900' : ($idx < $currentIdx ? 'text-indigo-600' : 'text-slate-400') }}">
                    {{ $label }}
                </span>
            </div>
            @if(!$loop->last)
                <div class="flex-1 h-px {{ $idx < $currentIdx ? 'bg-indigo-300' : 'bg-slate-100' }} max-w-16"></div>
            @endif
        @endforeach
        <!-- Pago (externo) -->
        <div class="flex-1 h-px bg-slate-100 max-w-16"></div>
        <div class="flex items-center gap-2">
            <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-black bg-slate-100 text-slate-400">4</span>
            <span class="text-slate-400">Pago</span>
        </div>
    </div>
    @endif

    {{-- ── Flash error ────────────────────────────────────────────────────── --}}
    @if(session()->has('error'))
        <div class="mb-6 text-sm text-red-600 bg-red-50 p-4 rounded-2xl border border-red-100">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-12">

        {{-- ── LEFT PANEL ─────────────────────────────────────────────────── --}}
        <div class="flex-1">

            {{-- PASO 1: Carrito --}}
            @if($step === 'cart')
                @forelse($items as $item)
                    @php
                        $img = $item->earphone->colors[0]['image'] ?? null;
                        $imgUrl = $img
                            ? (str_contains($img, 'images/') ? asset($img) : Storage::url($img))
                            : asset('images/placeholder.png');
                    @endphp
                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-50 flex items-center gap-6 group hover:shadow-md transition mb-4">
                        <div class="w-24 h-24 bg-slate-50 rounded-2xl flex items-center justify-center p-4 overflow-hidden flex-shrink-0">
                            <img src="{{ $imgUrl }}" alt="{{ $item->earphone->name }}" class="w-full h-full object-contain">
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="text-base font-bold text-slate-900 mb-1 truncate">{{ $item->earphone->name }}</h3>
                            <p class="text-sm text-slate-400 mb-4 truncate">{{ $item->earphone->description }}</p>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center bg-slate-50 rounded-lg p-1 border border-slate-100">
                                    <button wire:click="updateQuantity({{ $item->idCart_Item }}, {{ $item->quantity - 1 }})" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition">−</button>
                                    <span class="w-10 text-center font-bold text-slate-700">{{ $item->quantity }}</span>
                                    <button wire:click="updateQuantity({{ $item->idCart_Item }}, {{ $item->quantity + 1 }})" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition">+</button>
                                </div>
                                <button wire:click="removeItem({{ $item->idCart_Item }})" class="text-xs font-bold text-red-400 hover:text-red-600 transition uppercase tracking-wider">
                                    Eliminar
                                </button>
                            </div>
                        </div>

                        <div class="text-right flex-shrink-0">
                            <p class="text-xs text-slate-400 mb-1">${{ number_format($item->unit_price, 2) }} c/u</p>
                            <p class="text-xl font-black text-slate-900">${{ number_format($item->subtotal, 2) }}</p>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-[3rem] py-20 text-center border border-slate-100 shadow-sm">
                        <div class="mb-6 flex justify-center opacity-10">
                            <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-slate-900 mb-2">Tu carrito está vacío</h3>
                        <p class="text-slate-500 mb-8">Parece que aún no has elegido tu próximo sonido.</p>
                        <a href="{{ route('headphones') }}" class="inline-flex px-8 py-3 bg-indigo-600 text-white rounded-full font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                            Ver catálogo
                        </a>
                    </div>
                @endforelse
            @endif

            {{-- PASO 2: Dirección --}}
            @if($step === 'address')
                <div class="mb-6 flex items-center gap-3">
                    <button wire:click="backToCart" class="text-sm text-slate-400 hover:text-slate-700 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Volver al carrito
                    </button>
                </div>

                <h2 class="text-xl font-bold text-slate-900 mb-6">¿A dónde enviamos tu pedido?</h2>

                @if($addresses->isEmpty())
                    <div class="bg-white rounded-[2rem] p-10 text-center border border-slate-100 shadow-sm">
                        <p class="text-slate-500 mb-4">No tienes direcciones guardadas.</p>
                        <a href="{{ route('addresses') }}" class="inline-flex px-6 py-3 bg-indigo-600 text-white rounded-full font-bold text-sm hover:bg-indigo-700 transition">
                            Agregar dirección
                        </a>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach($addresses as $address)
                            <div class="bg-white rounded-[1.5rem] p-5 border-2 transition cursor-pointer
                                {{ $selectedAddressId === $address->idAddress ? 'border-indigo-500 shadow-md shadow-indigo-50' : 'border-slate-100 hover:border-slate-200' }}"
                                wire:click="confirmAddress({{ $address->idAddress }})"
                            >
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 mt-0.5
                                            {{ $selectedAddressId === $address->idAddress ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">
                                                {{ $address->street }} {{ $address->number }}
                                                @if($address->is_default)
                                                    <span class="ml-2 text-xs font-semibold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full">Predeterminada</span>
                                                @endif
                                            </p>
                                            <p class="text-sm text-slate-500 mt-0.5">{{ $address->colony }}, {{ $address->city }}, {{ $address->state }} {{ $address->zip }}</p>
                                        </div>
                                    </div>

                                    <div wire:loading wire:target="confirmAddress({{ $address->idAddress }})" class="flex-shrink-0">
                                        <svg class="animate-spin w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <a href="{{ route('addresses') }}" class="flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-800 transition pt-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Agregar nueva dirección
                        </a>
                    </div>
                @endif
            @endif

            {{-- PASO 3: Tarifas de envío --}}
            @if($step === 'shipping')
                <div class="mb-6 flex items-center gap-3">
                    <button wire:click="backToAddress" class="text-sm text-slate-400 hover:text-slate-700 transition flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Cambiar dirección
                    </button>
                </div>

                <h2 class="text-xl font-bold text-slate-900 mb-2">Elige tu servicio de envío</h2>
                <p class="text-sm text-slate-400 mb-6">Cotizado en tiempo real para tu dirección.</p>

                @if(empty($rates))
                    <div class="bg-slate-50 rounded-[2rem] p-10 text-center">
                        <p class="text-slate-400">No se encontraron opciones de envío para esta dirección.</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($rates as $rate)
                            @php $key = $rate['carrier'] . '|' . $rate['service']; @endphp
                            <div
                                wire:click="selectRate('{{ $key }}')"
                                class="bg-white rounded-[1.5rem] p-5 border-2 transition cursor-pointer
                                    {{ $selectedRate === $key ? 'border-indigo-500 shadow-md shadow-indigo-50' : 'border-slate-100 hover:border-slate-200' }}"
                            >
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0
                                            {{ $selectedRate === $key ? 'bg-indigo-600 text-white' : 'bg-slate-100 text-slate-500' }}">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 .01M13 16l2-8M3 16h10m0 0l4-8h2a2 2 0 012 2v6h-3"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-900 text-sm">{{ strtoupper($rate['carrier']) }} — {{ $rate['serviceDescription'] ?? $rate['service'] }}</p>
                                            @if(isset($rate['deliveryEstimate']))
                                                <p class="text-xs text-slate-400 mt-0.5">{{ $rate['deliveryEstimate'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-lg font-black {{ $selectedRate === $key ? 'text-indigo-600' : 'text-slate-900' }} flex-shrink-0">
                                        ${{ number_format((float) $rate['totalPrice'], 2) }}
                                        <span class="text-xs font-medium text-slate-400">MXN</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif

        </div>

        {{-- ── RIGHT PANEL: Resumen ─────────────────────────────────────────── --}}
        @if($items->count() > 0)
        <div class="w-full lg:w-80 xl:w-96">
            <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-100 border border-slate-50 sticky top-24">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Resumen</h3>

                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm text-slate-500">
                        <span>Productos ({{ $items->sum('quantity') }})</span>
                        <span class="font-bold text-slate-700">${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm text-slate-500">
                        <span>Envío</span>
                        @if($shippingCost > 0)
                            <span class="font-bold text-slate-700">${{ number_format($shippingCost, 2) }}</span>
                        @elseif($step === 'cart')
                            <span class="text-slate-400 italic">Por cotizar</span>
                        @elseif($step === 'address')
                            <span class="text-slate-400 italic">Selecciona dirección</span>
                        @else
                            <span class="text-slate-400 italic">Selecciona servicio</span>
                        @endif
                    </div>
                </div>

                <div class="pt-5 border-t border-slate-100 mb-8">
                    <div class="flex justify-between items-end">
                        <span class="text-slate-900 font-bold">Total</span>
                        <span class="text-3xl font-black text-indigo-600">${{ number_format($total, 2) }}</span>
                    </div>
                </div>

                @auth
                    @if($step === 'cart')
                        <button
                            wire:click="proceedToAddress"
                            class="w-full py-4 bg-indigo-600 text-white rounded-[1.25rem] font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition active:scale-95 text-base"
                        >
                            Continuar al pago
                        </button>

                    @elseif($step === 'address')
                        <button
                            disabled
                            class="w-full py-4 bg-slate-100 text-slate-400 rounded-[1.25rem] font-bold text-base cursor-not-allowed"
                        >
                            Selecciona una dirección
                        </button>

                    @elseif($step === 'shipping')
                        <button
                            wire:click="checkout"
                            wire:loading.attr="disabled"
                            @if(!$selectedRate) disabled @endif
                            class="w-full py-4 rounded-[1.25rem] font-bold text-base transition active:scale-95
                                {{ $selectedRate
                                    ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100 hover:bg-indigo-700'
                                    : 'bg-slate-100 text-slate-400 cursor-not-allowed' }}"
                        >
                            <span wire:loading.remove wire:target="checkout">
                                @if($selectedRate)
                                    Pagar ${{ number_format($total, 2) }}
                                @else
                                    Selecciona un envío
                                @endif
                            </span>
                            <span wire:loading wire:target="checkout" class="flex items-center justify-center gap-2">
                                <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                Redirigiendo...
                            </span>
                        </button>
                    @endif
                @else
                    <a href="{{ route('login') }}"
                       class="w-full block text-center py-4 bg-slate-900 text-white rounded-[1.25rem] font-bold hover:bg-slate-800 transition text-base">
                        Inicia sesión para pagar
                    </a>
                @endauth

                <p class="text-center text-xs text-slate-400 mt-5">
                    Pago seguro · Envío via envia.com
                </p>
            </div>
        </div>
        @endif

    </div>
</div>
