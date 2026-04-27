<div class="space-y-4">
    @forelse($orders as $order)
        @php
            $statusColor = match($order->status) {
                'completed', 'paid'      => ['bg' => 'bg-green-100',  'text' => 'text-green-700',  'dot' => 'bg-green-500'],
                'pending', 'processing'  => ['bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'dot' => 'bg-amber-500'],
                'cancelled', 'failed'    => ['bg' => 'bg-red-100',    'text' => 'text-red-700',    'dot' => 'bg-red-500'],
                'shipped', 'delivering'  => ['bg' => 'bg-blue-100',   'text' => 'text-blue-700',   'dot' => 'bg-blue-500'],
                default                  => ['bg' => 'bg-slate-100',  'text' => 'text-slate-600',  'dot' => 'bg-slate-400'],
            };
            $isExpanded = in_array($order->idOrder, $expanded);
        @endphp

        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">
            <!-- Order Header -->
            <button
                wire:click="toggle({{ $order->idOrder }})"
                class="w-full text-left px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 hover:bg-slate-50 transition"
            >
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-widest mb-0.5">Orden #{{ $order->idOrder }}</p>
                        <p class="text-sm text-slate-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-4 sm:gap-6">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $statusColor['bg'] }} {{ $statusColor['text'] }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $statusColor['dot'] }}"></span>
                        {{ ucfirst($order->status ?? 'pendiente') }}
                    </span>

                    <div class="text-right">
                        <p class="text-xs text-slate-400 font-medium">Total</p>
                        <p class="text-lg font-extrabold text-slate-900">${{ number_format($order->totalAmount ?? $order->orderItems->sum('subtotal'), 2) }}</p>
                    </div>

                    <svg
                        class="w-5 h-5 text-slate-400 transition-transform duration-200 flex-shrink-0 {{ $isExpanded ? 'rotate-180' : '' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>

            <!-- Order Items (expanded) -->
            @if($isExpanded)
                <div class="border-t border-slate-100 px-6 py-5 space-y-4">
                    @foreach($order->orderItems as $item)
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center p-2 flex-shrink-0 overflow-hidden">
                                @if($item->earphone)
                                    @php
                                        $img = $item->earphone->colors[0]['image'] ?? null;
                                        $imgUrl = $img
                                            ? (str_contains($img, 'images/') ? asset($img) : \Illuminate\Support\Facades\Storage::url($img))
                                            : asset('images/placeholder.png');
                                    @endphp
                                    <img src="{{ $imgUrl }}" alt="{{ $item->earphone->name }}" class="w-full h-full object-contain">
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-900 truncate">
                                    {{ $item->earphone?->name ?? 'Producto eliminado' }}
                                </p>
                                <p class="text-xs text-slate-400">
                                    {{ $item->quantity }} × ${{ number_format($item->unit_price, 2) }}
                                </p>
                            </div>

                            <p class="text-sm font-extrabold text-slate-900 flex-shrink-0">
                                ${{ number_format($item->subtotal, 2) }}
                            </p>
                        </div>
                    @endforeach

                    <div class="pt-4 border-t border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-2 text-sm text-slate-500">
                            <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10l2 .01M13 16l2-8M3 16h10m0 0l4-8h2a2 2 0 012 2v6h-3"/>
                            </svg>
                            
                            @if(isset($trackingData[$order->idOrder]))
                                @php $td = $trackingData[$order->idOrder]; @endphp
                                
                                @if(isset($td['error']))
                                    <span>Rastreo: <span class="text-slate-400 italic">{{ $td['error'] }}</span></span>
                                @else
                                    <div class="flex flex-col">
                                        <span>
                                            Rastreo {{ strtoupper($td['carrier'] ?? 'Envío') }}: 
                                            <span class="font-bold text-slate-700">{{ $order->TrackingNumber }}</span>
                                        </span>
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 uppercase tracking-wider">
                                                <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                                {{ $td['status'] }}
                                            </span>
                                            @if(!empty($td['estimatedDelivery']))
                                                <span class="text-xs text-slate-400">
                                                    Estimado: {{ \Carbon\Carbon::parse($td['estimatedDelivery'])->format('d M') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @else
                                <div class="flex items-center gap-2">
                                    <svg class="animate-spin w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                    </svg>
                                    <span>Cargando información de rastreo...</span>
                                </div>
                            @endif
                        </div>

                        @if(isset($trackingData[$order->idOrder]['url']) && $trackingData[$order->idOrder]['url'])
                            <a href="{{ $trackingData[$order->idOrder]['url'] }}" target="_blank" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl text-xs font-bold transition">
                                Ver en envia.com
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </a>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-[3rem] py-24 text-center border border-slate-100 shadow-sm">
            <div class="mb-6 flex justify-center opacity-10">
                <svg class="w-24 h-24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h3 class="text-2xl font-bold text-slate-900 mb-2">Aún no tienes órdenes</h3>
            <p class="text-slate-500 mb-8">Cuando realices tu primera compra aparecerá aquí.</p>
            <a href="{{ route('headphones') }}" class="inline-flex px-8 py-3 bg-indigo-600 text-white rounded-full font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition">
                Ver catálogo
            </a>
        </div>
    @endforelse

    @if($orders->hasPages())
        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    @endif
</div>
