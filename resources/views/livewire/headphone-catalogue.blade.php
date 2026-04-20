<div x-data="{ filtersOpen: false }" class="flex flex-col lg:flex-row gap-8 py-8 lg:py-12">
    <!-- Mobile Filter Toggle -->
    <div class="lg:hidden flex items-center justify-between mb-2">
        <button 
            @click="filtersOpen = !filtersOpen" 
            class="flex items-center space-x-2 bg-slate-900 text-white px-5 py-2.5 rounded-2xl font-bold text-sm shadow-xl active:scale-95 transition"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path></svg>
            <span x-text="filtersOpen ? 'Ocultar Filtros' : 'Filtros'"></span>
        </button>
        <span class="text-xs font-bold text-slate-400">{{ $products->total() }} artículos</span>
    </div>

    <!-- Sidebar Filters -->
    <aside 
        :class="filtersOpen ? 'block' : 'hidden'"
        class="lg:block w-full lg:w-64 space-y-8 bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 h-fit lg:sticky lg:top-24"
    >
        <div>
            <h3 class="text-lg font-bold text-slate-900 mb-4">Buscar audífonos</h3>
            <div class="flex flex-wrap gap-2 mb-6">
                @foreach(['Huawei', 'Apple', 'Samsung'] as $brand)
                    <button 
                        wire:click="$toggle('selectedBrands', '{{ $brand }}')"
                        class="flex items-center px-3 py-1 rounded-full text-xs font-medium transition-all {{ in_array($brand, $selectedBrands) ? 'bg-indigo-600 text-white' : 'bg-slate-50 text-slate-600 hover:bg-slate-100' }}"
                    >
                        {{ $brand }}
                        @if(in_array($brand, $selectedBrands))
                            <svg class="ml-1 w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        @endif
                    </button>
                @endforeach
            </div>
        </div>

                <div class="mb-10">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest">Presupuesto</h4>
                        <span class="text-sm font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                            ${{ number_format($minPrice) }} - ${{ number_format($maxPrice) }}
                        </span>
                    </div>

                    <div x-data="{ 
                        min: @entangle('minPrice'), 
                        max: @entangle('maxPrice'),
                        minlimit: 0,
                        maxlimit: 10000,
                        get minPercent() { return (this.min / this.maxlimit) * 100 },
                        get maxPercent() { return (this.max / this.maxlimit) * 100 }
                    }" class="relative h-10 w-full mt-4 flex items-center">
                        
                        <!-- Slider Track -->
                        <div class="absolute w-full h-1.5 bg-slate-100 rounded-full"></div>
                        
                        <!-- Track Highlight -->
                        <div 
                            class="absolute h-1.5 bg-indigo-600 rounded-full"
                            :style="`left: ${minPercent}%; right: ${100 - maxPercent}%`"
                        ></div>

                        <!-- Hidden Range Inputs -->
                        <input 
                            type="range" 
                            x-model.number="min" 
                            min="0" 
                            max="10000" 
                            step="50"
                            class="absolute w-full h-2 opacity-0 cursor-pointer z-30 pointer-events-auto"
                            @input="if(min > max - 100) min = max - 100"
                        >
                        <input 
                            type="range" 
                            x-model.number="max" 
                            min="0" 
                            max="10000" 
                            step="50"
                            class="absolute w-full h-2 opacity-0 cursor-pointer z-30 pointer-events-auto"
                            @input="if(max < min + 100) max = min + 100"
                        >

                        <!-- Custom Handles -->
                        <div 
                            class="absolute w-6 h-6 bg-white border-2 border-indigo-600 rounded-full -ml-3 shadow-lg pointer-events-none z-40 flex items-center justify-center transition-transform"
                            :style="`left: ${minPercent}%`"
                        >
                            <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></div>
                        </div>
                        <div 
                            class="absolute w-6 h-6 bg-white border-2 border-indigo-600 rounded-full -ml-3 shadow-lg pointer-events-none z-40 flex items-center justify-center transition-transform"
                            :style="`left: ${maxPercent}%`"
                        >
                            <div class="w-1.5 h-1.5 bg-indigo-600 rounded-full"></div>
                        </div>
                    </div>
                    
                    <div class="flex justify-between mt-2 text-[10px] font-bold text-slate-300">
                        <span>Min $0</span>
                        <span>Max $10000+</span>
                    </div>
                </div>

        <!-- Color -->
        <div>
            <h3 class="text-sm font-bold text-slate-900 mb-4">Color</h3>
            <div class="space-y-3">
                @foreach(['Azul', 'Blanco', 'Negro'] as $color)
                    <label class="flex items-center group cursor-pointer">
                        <input type="checkbox" wire:model.live="colors" value="{{ $color }}" class="w-4 h-4 text-indigo-600 border-slate-200 rounded focus:ring-indigo-500">
                        <span class="ml-3 text-sm text-slate-600 group-hover:text-slate-900 transition">{{ $color }}</span>
                    </label>
                @endforeach
            </div>
        </div>

        <!-- Type -->
        <div>
            <h3 class="text-sm font-bold text-slate-900 mb-4">Tipo</h3>
            <div class="space-y-3">
                @foreach(['Bluetooth', 'Cable', 'Híbrido'] as $type)
                    <label class="flex items-center group cursor-pointer">
                        <input type="checkbox" wire:model.live="types" value="{{ $type }}" class="w-4 h-4 text-indigo-600 border-slate-200 rounded focus:ring-indigo-500">
                        <span class="ml-3 text-sm text-slate-600 group-hover:text-slate-900 transition">{{ $type }}</span>
                    </label>
                @endforeach
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1">
        <!-- Search Bar Top -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div class="relative flex-1 max-w-md">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar..." 
                    class="w-full pl-12 pr-4 py-3 bg-white border border-slate-100 rounded-full text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent shadow-sm"
                >
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </div>

            <div class="flex items-center space-x-2">
                <button class="px-6 py-2.5 bg-slate-900 text-white rounded-full text-sm font-bold shadow-lg hover:bg-slate-800 transition">Buscar</button>
                @foreach(['Apple', 'Huawei', 'Samsung'] as $quickFilter)
                    <button 
                        wire:click="$set('search', '{{ $quickFilter }}')"
                        class="px-4 py-2.5 bg-white border border-slate-100 rounded-full text-sm font-bold text-slate-600 hover:bg-slate-50 transition"
                    >
                        {{ $quickFilter }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($products as $product)
                @php
                    $firstColor = $product->colors ? $product->colors[0] : null;
                    $defaultImage = $firstColor 
                        ? (str_contains($firstColor['image'], 'images/') ? asset($firstColor['image']) : Storage::url($firstColor['image']))
                        : asset('images/placeholder.png');
                @endphp

                <div 
                    x-data="{ activeImage: '{{ $defaultImage }}' }"
                    class="group bg-white rounded-[2.5rem] p-4 border border-slate-50 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col"
                >
                    <div class="aspect-square bg-slate-50 rounded-[2rem] flex items-center justify-center p-6 mb-6 overflow-hidden relative">
                        <img 
                            :src="activeImage" 
                            alt="{{ $product->name }}" 
                            class="w-4/5 h-4/5 object-contain group-hover:scale-110 transition-transform duration-500"
                        >
                        
                        <div class="absolute inset-0 bg-indigo-600/0 group-hover:bg-indigo-600/5 transition-colors duration-300"></div>
                    </div>
                    
                    <div class="px-2 pb-2 flex-1 flex flex-col">
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors mb-2 line-clamp-1">
                            {{ $product->name }}
                        </h3>

                        <!-- Colors Display -->
                        @if($product->colors)
                            <div class="flex items-center gap-1.5 mb-4">
                                @foreach($product->colors as $color)
                                    @php
                                        $colorImageUrl = str_contains($color['image'], 'images/') ? asset($color['image']) : Storage::url($color['image']);
                                        $isSelected = ($selectedColors[$product->idEarphone] ?? 0) == $loop->index;
                                    @endphp
                                    <button 
                                        @click="activeImage = '{{ $colorImageUrl }}'"
                                        wire:click="selectColor({{ $product->idEarphone }}, {{ $loop->index }})"
                                        class="w-4 h-4 rounded-full border shadow-sm transition transform hover:scale-125 focus:outline-none {{ $isSelected ? 'ring-2 ring-indigo-600 border-white' : 'border-slate-200' }}" 
                                        style="background-color: {{ $color['hex'] }}"
                                        title="Stock: {{ $color['stock'] }}"
                                    ></button>
                                @endforeach
                            </div>
                        @else
                            <div class="h-4 mb-4"></div>
                        @endif
                        <div class="flex items-center justify-between">
                            <span class="text-xl font-black text-slate-900">${{ number_format($product->price, 0) }}</span>
                            <div class="relative group/tooltip">
                                <button 
                                    wire:click="addToCart({{ $product->idEarphone }})"
                                    class="w-10 h-10 bg-slate-900 text-white rounded-xl flex items-center justify-center hover:bg-indigo-600 transition shadow-lg active:scale-95"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </button>
                                <!-- Tooltip -->
                                <span class="absolute -top-10 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-3 py-1.5 rounded-lg opacity-0 group-hover/tooltip:opacity-100 transition-all duration-300 pointer-events-none whitespace-nowrap shadow-xl">
                                    Agregar al carrito
                                    <div class="absolute -bottom-1 left-1/2 -translate-x-1/2 w-2 h-2 bg-slate-900 rotate-45"></div>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-[3rem] border border-slate-100">
                    <p class="text-slate-400 font-medium text-lg">No se encontraron audífonos con estos filtros.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-12">
            {{ $products->links() }}
        </div>
    </div>
</div>
