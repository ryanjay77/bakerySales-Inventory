<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Pan de Pane - Bakery System</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CUSTOM STYLES FOR IMPROVED READABILITY -->
    <style>
        /* 1. Set a clean, modern font family */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Soft gray background */
        }

        /* 2. Global Font Scaling (The Magic Part) */
        /* We override Tailwind's default sizes to be slightly larger */
        
        .text-xs {
            font-size: 0.85rem !important; /* Increased from 0.75rem */
            line-height: 1.25rem !important;
        }

        .text-sm {
            font-size: 0.95rem !important; /* Increased from 0.875rem */
            line-height: 1.4rem !important;
        }

        .text-base {
            font-size: 1.05rem !important; /* Increased from 1rem */
        }

        .text-lg {
            font-size: 1.25rem !important;
        }

        .text-xl {
            font-size: 1.4rem !important;
        }

        .text-2xl {
            font-size: 1.75rem !important;
        }

        /* 3. Make Inputs and Selects larger and more clickable */
        input[type="text"], 
        input[type="number"], 
        input[type="email"], 
        input[type="password"], 
        input[type="date"],
        select {
            font-size: 1rem !important; /* Standard reading size */
            padding-top: 0.6rem !important;
            padding-bottom: 0.6rem !important;
        }

        /* 4. Improve Table Readability */
        th {
            font-size: 0.9rem !important;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
        
        td {
            padding-top: 1rem !important;
            padding-bottom: 1rem !important;
        }
    </style>
</head>
<body class="font-sans antialiased">
    <div class="flex min-h-screen">
        
        <!-- Sidebar -->
        <aside class="w-72 bg-white border-r border-gray-200 fixed h-full z-20 hidden md:flex flex-col shadow-sm">
            <div class="p-6 flex items-center gap-4 border-b border-gray-100">
                <div class="bg-amber-500 p-3 rounded-xl text-white shadow-md">
                    <!-- Icon -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 11-1 9"/><path d="m19 11-4-7"/><path d="M2 11h20"/><path d="m3.5 11 1.6 7.4a2 2 0 0 0 2 1.6h9.8a2 2 0 0 0 2-1.6l1.7-7.4"/><path d="m4.5 11 4-7"/><path d="m9 11 1 9"/></svg>
                </div>
                <div>
                    <h1 class="font-bold text-xl tracking-tight text-gray-900 leading-tight">Pan de Pane</h1>
                    <p class="text-sm text-gray-500 font-medium">Bakeshoppe System</p>
                </div>
            </div>

            <nav class="flex-1 p-5 space-y-3 overflow-y-auto">
                <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-4 mt-2">Menu</div>
                
                <!-- ADMIN LINKS -->
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-4 px-5 py-4 rounded-xl transition-all duration-200 group {{ request()->routeIs('dashboard') ? 'bg-amber-50 text-amber-700 font-bold border border-amber-100 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="text-xl group-hover:scale-110 transition-transform">📊</span> 
                        <span class="text-base">Dashboard</span>
                    </a>
                    
                    <a href="{{ route('products.index') }}" class="flex items-center gap-4 px-5 py-4 rounded-xl transition-all duration-200 group {{ request()->routeIs('products.index') ? 'bg-amber-50 text-amber-700 font-bold border border-amber-100 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="text-xl group-hover:scale-110 transition-transform">📝</span> 
                        <span class="text-base">Product Entry</span>
                    </a>

                    <a href="{{ route('stock-in.index') }}" class="flex items-center gap-4 px-5 py-4 rounded-xl transition-all duration-200 group {{ request()->routeIs('stock-in.index') ? 'bg-amber-50 text-amber-700 font-bold border border-amber-100 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="text-xl group-hover:scale-110 transition-transform">📥</span> 
                        <span class="text-base">Stock In</span>
                    </a>

                    <a href="{{ route('stock-out.index') }}" class="flex items-center gap-4 px-5 py-4 rounded-xl transition-all duration-200 group {{ request()->routeIs('stock-out.index') ? 'bg-amber-50 text-amber-700 font-bold border border-amber-100 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="text-xl group-hover:scale-110 transition-transform">📤</span> 
                        <span class="text-base">Stock Out</span>
                    </a>

                    <a href="{{ route('users.index') }}" class="flex items-center gap-4 px-5 py-4 rounded-xl transition-all duration-200 group {{ request()->routeIs('users.index') ? 'bg-amber-50 text-amber-700 font-bold border border-amber-100 shadow-sm' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="text-xl group-hover:scale-110 transition-transform">👥</span> 
                        <span class="text-base">User Accounts</span>
                    </a>
                @endif
                
                <!-- SHARED LINK -->
                <div class="pt-4 mt-4 border-t border-gray-100">
                    <div class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3 px-4">Sales</div>
                    <a href="{{ route('pos.index') }}" class="flex items-center gap-4 px-5 py-4 rounded-xl transition-all duration-200 group {{ request()->routeIs('pos.index') ? 'bg-amber-600 text-white font-bold shadow-md hover:bg-amber-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <span class="text-xl group-hover:scale-110 transition-transform">💳</span> 
                        <span class="text-base">Point of Sale</span>
                    </a>
                </div>
            </nav>

            <!-- User Profile -->
            <div class="p-5 border-t border-gray-100 bg-gray-50">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-white text-amber-600 border border-amber-100 flex items-center justify-center font-bold text-xl shadow-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-base font-bold text-gray-900 truncate capitalize">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-gray-500 truncate">{{ ucfirst(auth()->user()->role) }}</p>
                    </div>
                </div>
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-3 px-4 py-3 bg-white border border-gray-200 text-red-500 hover:bg-red-50 hover:border-red-100 rounded-xl transition-colors text-base font-bold shadow-sm">
                        🚪 Sign Out
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 md:ml-72 p-8 transition-all">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl relative shadow-sm flex items-center gap-3">
                    <span class="text-2xl">✅</span>
                    <span class="font-medium text-lg">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl relative shadow-sm flex items-center gap-3">
                    <span class="text-2xl">❌</span>
                    <span class="font-medium text-lg">{{ session('error') }}</span>
                </div>
            @endif
            
            {{ $slot }}
        </main>
    </div>
</body>
</html>