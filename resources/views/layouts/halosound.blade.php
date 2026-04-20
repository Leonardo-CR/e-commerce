<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'HaloSound')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    
    <style>
        :root {
            --primary: #6366f1;
            --bg-soft: #fdfcfb;
        }
        body { 
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-soft);
        }
        .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body x-data="{}" class="antialiased text-slate-900 selection:bg-indigo-100 selection:text-indigo-900">

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
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
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

    <main class="max-w-7xl mx-auto px-6 pt-24 min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white pt-24 pb-12 border-t border-slate-100 mt-20">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
            <div class="md:col-span-2">
                <div class="flex items-center space-x-3 mb-6">
                    <img src="{{ asset('images/image 3@2x.png') }}" alt="HaloSound Logo" class="h-8 w-auto">
                    <span class="text-xl font-bold tracking-tight text-slate-900">HaloSound</span>
                </div>
                <p class="text-slate-500 max-w-sm mb-8 leading-relaxed">
                    Forjando el futuro del sonido a través de la innovación y la calidad sin compromisos. 
                </p>
                <div class="flex space-x-4 text-slate-400">
                    <a href="#" class="hover:text-indigo-600 transition">Twitter</a>
                    <a href="#" class="hover:text-indigo-600 transition">Instagram</a>
                    <a href="#" class="hover:text-indigo-600 transition">LinkedIn</a>
                </div>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-6">Explorar</h4>
                <ul class="space-y-4 text-sm text-slate-500">
                    <li><a href="/headphones" class="hover:text-indigo-600 transition">Auriculares</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Accesorios</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-slate-900 mb-6">Recursos</h4>
                <ul class="space-y-4 text-sm text-slate-500">
                    <li><a href="#" class="hover:text-indigo-600 transition">Blog</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Colores</a></li>
                    <li><a href="#" class="hover:text-indigo-600 transition">Soporte</a></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-6 pt-8 border-t border-slate-50 text-center md:text-left">
            <p class="text-sm text-slate-400">&copy; {{ date('Y') }} HaloSound Inc. support@halosound.com</p>
        </div>
    </footer>

    <x-auth-modal />
    <x-logout-modal />
    @livewireScripts
</body>
</html>
