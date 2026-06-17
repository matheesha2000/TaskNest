<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TaskNest') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-700 antialiased bg-dark-bg">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden px-4">
            
            {{-- Background decorative mesh --}}
            <div class="absolute top-[-20%] left-[-20%] w-[60%] h-[60%] bg-indigo-500/8 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-[-20%] right-[-20%] w-[60%] h-[60%] bg-purple-500/8 rounded-full blur-3xl pointer-events-none"></div>

            <div class="mb-4 relative z-10 animate-float">
                <a href="/" class="flex items-center gap-2.5 focus:outline-none">
                    <span class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-indigo-500 to-violet-500 flex items-center justify-center text-white font-extrabold text-lg shadow-md shadow-indigo-500/20">T</span>
                    <span class="text-2xl font-extrabold tracking-tight text-slate-800">Task<span class="bg-gradient-to-r from-indigo-600 to-violet-600 bg-clip-text text-transparent">Nest</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-4 px-8 py-8 bg-white/85 border border-slate-200/80 shadow-xl shadow-slate-200/40 backdrop-blur-xl sm:rounded-2xl relative z-10">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
