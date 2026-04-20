<!DOCTYPE html>
<html lang="es" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Página no encontrada | HaloSound</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #fdfcfb; }
        .text-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .floating {
            animation: floating 3s ease-in-out infinite;
        }
        @keyframes floating {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-6">
    <div class="max-w-2xl w-full text-center">
        <!-- Icon/Illustration -->
        <div class="relative mb-12 flex justify-center">
            <div class="absolute inset-0 bg-indigo-100 blur-[100px] opacity-50 rounded-full"></div>
            <div class="relative floating bg-white p-8 rounded-[3rem] shadow-2xl border border-slate-50">
                <svg class="w-24 h-24 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                </svg>
            </div>
        </div>

        <h1 class="text-9xl font-black text-slate-200 mb-4 tracking-tighter">404</h1>
        <h2 class="text-4xl font-extrabold text-slate-900 mb-6 tracking-tight">
            Parece que te has <span class="text-gradient">perdido en el silencio</span>
        </h2>
        <p class="text-lg text-slate-500 mb-12 max-w-md mx-auto leading-relaxed">
            La página que buscas no existe o ha sido movida. Pero no te preocupes, el ritmo continúa en nuestra tienda.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-4">
            <a href="/" class="px-8 py-4 bg-slate-900 text-white rounded-full font-bold shadow-xl hover:bg-slate-800 transition-all transform active:scale-95 w-full sm:w-auto">
                Volver al inicio
            </a>
            <a href="/headphones" class="px-8 py-4 bg-white text-indigo-600 border border-slate-100 rounded-full font-bold shadow-sm hover:shadow-md transition-all w-full sm:w-auto">
                Ver audífonos
            </a>
        </div>
        
        <div class="mt-20">
            <img src="{{ asset('images/image 3@2x.png') }}" alt="HaloSound" class="h-8 mx-auto opacity-30 grayscale">
        </div>
    </div>
</body>
</html>
