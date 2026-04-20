<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HaloSound | Experiencia de Audio Premium</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #e0e7ff;
            --accent: #f59e0b;
            --bg-soft: #fdfcfb;
        }
        
        body { 
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-soft);
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }

        .hero-gradient {
            background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.05) 0%, transparent 50%),
                        radial-gradient(circle at bottom left, rgba(245, 158, 11, 0.05) 0%, transparent 50%);
        }

        .product-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05);
        }

        .btn-premium {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            transition: all 0.3s ease;
        }

        .btn-premium:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 20px -5px rgba(15, 23, 42, 0.3);
        }

        .feature-card {
            background: white;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            border-color: var(--primary-light);
            background: var(--bg-soft);
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="antialiased text-slate-900 selection:bg-indigo-100 selection:text-indigo-900">

    <!-- Header Navigation -->
    <nav x-data="{ mobileMenuOpen: false }" class="fixed w-full z-50 glass-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-2 sm:space-x-3 shrink-0">
                    <img src="{{ asset('images/image 3@2x.png') }}" alt="HaloSound Logo" class="h-8 sm:h-10 w-auto">
                    <span class="text-lg sm:text-xl font-bold tracking-tight text-slate-900">HaloSound</span>
                </a>
                
                <!-- Desktop Nav Categories -->
                <div class="hidden lg:flex items-center space-x-8 text-sm font-medium text-slate-600">
                    <a href="/headphones" class="hover:text-indigo-600 transition">Colecciones</a>
                    <a href="#" class="hover:text-indigo-600 transition">Tecnología</a>
                    <a href="#" class="hover:text-indigo-600 transition">Sustentabilidad</a>
                </div>

                <div class="flex items-center space-x-2 sm:space-x-6">
                    @auth
                        <!-- Cart Icon -->
                        @livewire('cart-counter')

                        <!-- Desktop User Menu -->
                        <div class="hidden md:flex items-center space-x-4 border-l border-slate-200 pl-6 ml-2">
                            <a href="{{ route('profile.show') }}" class="text-sm font-bold text-slate-700 hover:text-indigo-600 transition">
                                Configuración
                            </a>
                            <button x-on:click="$dispatch('show-logout-modal')" class="text-sm font-bold text-red-500 hover:text-red-600 transition">
                                Salir
                            </button>
                        </div>
                    @else
                        <div class="hidden sm:flex items-center space-x-4">
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600">Iniciar Sesión</a>
                            <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-5 py-2.5 rounded-full text-sm font-bold shadow-lg shadow-indigo-200">Registrarse</a>
                        </div>
                    @endauth

                    <!-- Mobile Menu Button -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden p-2 text-slate-600 hover:bg-slate-50 rounded-xl transition">
                        <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                        <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div 
            x-show="mobileMenuOpen" 
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="lg:hidden bg-white border-t border-slate-100 shadow-xl"
            x-cloak
        >
            <div class="px-6 py-8 space-y-6">
                <div class="flex flex-col space-y-4">
                    <a href="/headphones" class="text-base font-bold text-slate-900">Colecciones</a>
                    <a href="#" class="text-base font-bold text-slate-900">Tecnología</a>
                    <a href="#" class="text-base font-bold text-slate-900">Sustentabilidad</a>
                </div>
                
                <div class="pt-6 border-t border-slate-50 flex flex-col space-y-4">
                    @auth
                        <a href="{{ route('profile.show') }}" class="text-base font-bold text-slate-600">Configuración</a>
                        <button x-on:click="$dispatch('show-logout-modal')" class="text-left text-base font-bold text-red-500">Salir</button>
                    @else
                        <a href="{{ route('login') }}" class="text-base font-bold text-slate-600">Iniciar Sesión</a>
                        <a href="{{ route('register') }}" class="inline-flex justify-center bg-indigo-600 text-white px-6 py-3 rounded-full text-base font-bold">Registrarse</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-20 overflow-hidden hero-gradient">
        <div class="max-w-7xl mx-auto px-6 sm:px-8 grid lg:grid-cols-2 gap-12 items-center">
            <div class="relative z-10">
                <span class="inline-block py-1 px-3 rounded-full bg-indigo-50 text-indigo-600 text-xs font-bold uppercase tracking-wider mb-6">Nueva Serie 2024</span>
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-slate-900 leading-[1.1] mb-6">
                    Redefiniendo la <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-indigo-400">Perfección Sonora</span>
                </h1>
                <p class="text-lg text-slate-500 mb-10 max-w-lg leading-relaxed">
                    Eleva tu experiencia auditiva con acústica de vanguardia y diseño ergonómico. Diseñado para quienes notan la diferencia.
                </p>
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                    <a href="/headphones" class="btn-premium px-8 py-4 text-white rounded-full font-bold text-center">Comprar Ahora</a>
                    <a href="#" class="px-8 py-4 text-slate-600 rounded-full font-bold border border-slate-200 hover:bg-white transition text-center flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" /></svg>
                        Ver Video
                    </a>
                </div>
                
                <div class="mt-12 flex items-center space-x-6">
                    <div class="flex -space-x-3">
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-200"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-300"></div>
                        <div class="w-10 h-10 rounded-full border-2 border-white bg-slate-400"></div>
                    </div>
                    <p class="text-sm text-slate-500"><span class="font-bold text-slate-900">+2,400</span> reseñas verificadas</p>
                </div>
            </div>
            
            <div class="relative">
                <div class="absolute -top-20 -right-20 w-80 h-80 bg-indigo-100 rounded-full blur-3xl opacity-60"></div>
                <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-amber-100 rounded-full blur-3xl opacity-60"></div>
                <img src="{{ asset('images/hero.png') }}" alt="Premium Headphones" class="relative z-10 w-full h-auto drop-shadow-2xl floating">
            </div>
        </div>
    </section>

    <!-- Features Bar -->
    <div class="bg-white border-y border-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center mb-4 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h4 class="font-bold text-slate-900">Audio Hi-Res</h4>
                <p class="text-xs text-slate-400">Frecuencia de 96kHz</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center mb-4 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h4 class="font-bold text-slate-900">48h de Batería</h4>
                <p class="text-xs text-slate-400">Carga rápida incluida</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center mb-4 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h4 class="font-bold text-slate-900">2 Años de Garantía</h4>
                <p class="text-xs text-slate-400">Soporte premium</p>
            </div>
            <div class="flex flex-col items-center text-center">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center mb-4 text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h4 class="font-bold text-slate-900">Envío Gratis</h4>
                <p class="text-xs text-slate-400">En pedidos sobre $99</p>
            </div>
        </div>
    </div>

    <!-- Products Section -->
    <section id="products" class="py-24">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div>
                    <h2 class="text-3xl md:text-5xl font-extrabold text-slate-900 mb-4">Sonido Curado <br> Para Tu Estilo de Vida</h2>
                    <p class="text-slate-500">Explora nuestras colecciones profesionales y casuales.</p>
                </div>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 border border-slate-200 rounded-lg text-sm font-bold hover:bg-white transition">Todo</button>
                    <button class="px-4 py-2 text-slate-500 rounded-lg text-sm font-bold hover:bg-white transition">Estudio</button>
                    <button class="px-4 py-2 text-slate-500 rounded-lg text-sm font-bold hover:bg-white transition">Inalámbricos</button>
                </div>
            </div>

            @if(isset($products) && $products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">
                    @foreach($products as $product)
                        @php
                            $firstColor = $product->colors ? $product->colors[0] : null;
                            $imageUrl = $firstColor 
                                ? (str_contains($firstColor['image'], 'images/') ? asset($firstColor['image']) : Storage::url($firstColor['image']))
                                : asset('images/placeholder.png');
                        @endphp
                        <div class="product-card group flex flex-col h-full bg-white rounded-[2.5rem] p-4 border border-slate-100 shadow-sm relative overflow-hidden">
                            <div class="relative aspect-square rounded-[2rem] overflow-hidden bg-slate-50 flex items-center justify-center mb-6">
                                <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="w-4/5 h-4/5 object-contain group-hover:scale-110 transition-transform duration-500">
                                <div class="absolute inset-0 bg-indigo-600/0 group-hover:bg-indigo-600/5 transition-colors"></div>
                            </div>
                            
                            <div class="px-2 flex-grow">
                                <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors mb-1 truncate">{{ $product->name }}</h3>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-4">
                                    {{ count($product->colors ?? []) }} Colores disponibles
                                </p>
                            </div>

                            <div class="mt-auto flex items-center justify-between pt-4 border-t border-slate-50">
                                <span class="text-xl font-black text-slate-900">${{ number_format($product->price, 0) }}</span>
                                <a href="/headphones" class="bg-indigo-600 p-3 rounded-xl shadow-lg shadow-indigo-100 text-white hover:bg-slate-900 transition-all transform active:scale-95">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- View All Button -->
                <div class="mt-16 text-center">
                    <a href="/headphones" class="inline-flex items-center space-x-3 bg-slate-900 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all shadow-xl shadow-slate-200 group">
                        <span>Ver Colección Completa</span>
                        <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm">
                    <p class="text-slate-500 text-lg">No se encontraron productos. Mantente atento a nuestras novedades.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Lifestyle Section -->
    <section class="py-24 px-6">
        <div class="max-w-7xl mx-auto rounded-[3rem] overflow-hidden relative bg-slate-900 min-h-[500px] flex items-center">
            <img src="{{ asset('images/lifestyle.png') }}" class="absolute inset-0 w-full h-full object-cover opacity-60" alt="Lifestyle">
            <div class="relative z-10 p-8 md:p-16 max-w-2xl">
                <h2 class="text-4xl md:text-5xl font-extrabold text-white mb-6">Diseñado para el <br> Silencio Perfecto</h2>
                <p class="text-lg text-slate-200 mb-10 leading-relaxed">
                    La tecnología avanzada de Cancelación Activa de Ruido garantiza que tu enfoque permanezca donde debe: en la música.
                </p>
                <a href="#" class="inline-flex items-center space-x-4 group">
                    <span class="w-14 h-14 bg-white rounded-full flex items-center justify-center text-slate-900 shadow-xl group-hover:scale-110 transition">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                    </span>
                    <span class="text-white font-bold text-lg">Explorar Tecnología</span>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white pt-24 pb-12 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <div class="md:col-span-2">
                <div class="flex items-center space-x-3 mb-6">
                    <img src="{{ asset('images/image 3@2x.png') }}" alt="HaloSound Logo" class="h-8 w-auto">
                    <span class="text-xl font-bold tracking-tight text-slate-900">HaloSound</span>
                </div>
                <p class="text-slate-500 max-w-sm mb-8 leading-relaxed">
                    Forjando el futuro del sonido a través de la innovación, la sustentabilidad y una calidad sin compromisos.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-600 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-600 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    </a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-6">Explorar</h4>
                <ul class="space-y-4 text-sm text-slate-500">
                    <li><a href="#" class="hover:text-indigo-600 transition">Auriculares</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Accesorios</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Localizador de Tiendas</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Referir Amigo</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-6">Soporte</h4>
                <ul class="space-y-4 text-sm text-slate-500">
                    <li><a href="#" class="hover:text-indigo-600 transition">Centro de Ayuda</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Info de Envío</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Devoluciones</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Contacto</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 pt-8 border-t border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-sm text-slate-400">&copy; {{ date('Y') }} HaloSound Inc. Diseñado con pasión.</p>
            <div class="flex space-x-6 text-sm text-slate-400">
                <a href="#" class="hover:text-slate-900 transition">Política de Privacidad</a>
                <a href="#" class="hover:text-slate-900 transition">Términos de Servicio</a>
            </div>
        </div>
    </footer>

</body>
</html>
