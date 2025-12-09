<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Bakery System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- THIS SCRIPT IS CRITICAL FOR STYLING -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r border-gray-200 fixed h-full z-10 hidden md:flex flex-col">
            <div class="p-6 flex items-center gap-3 border-b border-gray-100">
                <div class="bg-amber-500 p-2 rounded-lg text-white">
                    <!-- Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 11-1 9"/><path d="m19 11-4-7"/><path d="M2 11h20"/><path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.7-7.4"/><path d="m4.5 11 4-7"/><path d="m9 11 1 9"/></svg>
                </div>
                <div>
                    <h1 class="font-bold text-xl tracking-tight text-gray-900">Pan de pane</h1>
                    <p class="text-xs text-gray-400">Bakery Manager</p>
                </div>
            </div>

            <nav class="flex-1 p-4 space-y-2">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2 px-4 mt-4">Menu</div>
                
                <!-- ADMIN LINKS -->
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'bg-amber-100 text-amber-900 font-bold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
        <span>📊</span> Dashboard
    </a>

    <!-- RENAMED: Product Entry -->
    <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('products.index') ? 'bg-amber-100 text-amber-900 font-bold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
        <span>📝</span> Product Entry
    </a>

    <!-- NEW: Stock In -->
    <a href="{{ route('stock-in.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('stock-in.index') ? 'bg-amber-100 text-amber-900 font-bold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
        <span>📥</span> Stock In
    </a>

    <a href="{{ route('stock-out.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('stock-out.index') ? 'bg-amber-100 text-amber-900 font-bold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
    <span>📤</span> Stock Out
</a>

    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('users.index') ? 'bg-amber-100 text-amber-900 font-bold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
        <span>👥</span> User Management
    </a>
                @endif
                
                <!-- SHARED LINK -->
                <a href="{{ route('pos.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('pos.index') ? 'bg-amber-100 text-amber-900 font-bold' : 'text-gray-500 hover:bg-gray-100 hover:text-gray-900' }}">
                    <span>💳</span> Point of Sale
                </a>
            </nav>

            <div class="p-4 border-t border-gray-100">
                <div class="flex items-center gap-3 px-4 py-3 mb-2">
                    <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-gray-900 truncate capitalize">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors text-sm font-medium">
                        🚪 Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 md:ml-64 p-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            
            {{ $slot }}
        </main>
    </div>
</body>
</html>