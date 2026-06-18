<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — {{ config('app.name') }} · @yield('title')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand:  { 50:'#eef2ff',100:'#e0e7ff',500:'#6366f1',600:'#4f46e5',700:'#4338ca' },
                        admin:  { bg:'#0f172a', hover:'#1e293b', active:'#334155' }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-slate-100 antialiased text-slate-800">

<div class="flex h-screen overflow-hidden">

    {{-- ── Admin Sidebar (dark) ─────────────────────────────────────────── --}}
    <aside class="w-60 flex-shrink-0 bg-slate-900 flex flex-col">

        {{-- Logo --}}
        <div class="h-16 flex items-center px-5 border-b border-slate-800">
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-brand-500 flex items-center justify-center text-white text-xs font-bold">A</span>
                <div>
                    <p class="text-white text-sm font-bold leading-none">TaskNest</p>
                    <p class="text-slate-400 text-xs">Admin Panel</p>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-5 space-y-0.5 overflow-y-auto">

            @php
                $navItems = [
                    ['route' => 'admin.dashboard', 'label' => 'Dashboard',    'icon' => 'M3 7h18M3 12h18M3 17h18'],
                    ['route' => 'admin.users',     'label' => 'Users',        'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                    ['route' => 'admin.payments',  'label' => 'Payments',     'icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                    ['route' => 'admin.reviews',   'label' => 'Reviews',      'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'],
                    ['route' => 'admin.tasks',     'label' => 'Task Monitor', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $active = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors
                          {{ $active ? 'bg-brand-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-slate-100' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="pt-4 mt-4 border-t border-slate-800">
                <a href="{{ route('dashboard') }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-slate-400 hover:bg-slate-800 hover:text-slate-100 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to App
                </a>
            </div>
        </nav>

        {{-- Admin user footer --}}
        <div class="border-t border-slate-800 p-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-brand-500 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                    <p class="text-slate-400 text-xs">Administrator</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout"
                            class="text-slate-500 hover:text-red-400 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── Main Content ─────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col overflow-hidden">

        {{-- Top bar --}}
        <header class="h-16 bg-white border-b border-slate-200 flex items-center px-6 gap-4 flex-shrink-0">
            <div class="flex-1">
                <h1 class="text-base font-semibold text-slate-700">@yield('title')</h1>
                <p class="text-xs text-slate-400">@yield('subtitle', 'TaskFlow Admin Panel')</p>
            </div>
            <div class="flex items-center gap-2 text-xs text-slate-400">
                <span class="w-2 h-2 rounded-full bg-green-400 inline-block"></span>
                System Online
            </div>
        </header>

        {{-- Flash messages --}}
        <div class="px-6 pt-3 space-y-2">
            @foreach(['success' => 'green', 'error' => 'red', 'info' => 'blue'] as $type => $color)
                @if(session($type))
                    <div class="rounded-lg bg-{{ $color }}-50 border border-{{ $color }}-200 text-{{ $color }}-800 px-4 py-2.5 text-sm flex items-center justify-between">
                        <span>{{ session($type) }}</span>
                        <button onclick="this.parentElement.remove()" class="text-{{ $color }}-400 hover:text-{{ $color }}-600 ml-4">✕</button>
                    </div>
                @endif
            @endforeach
        </div>

        {{-- Page --}}
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>

</div>
</body>
</html>