<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery System</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-100 flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white h-screen fixed border-r flex flex-col">
        <div class="p-6 border-b">
            <h1 class="font-bold text-amber-600 text-xl">Golden Crust</h1>
        </div>
        <nav class="flex-1 p-4 space-y-2">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-amber-50 text-gray-700">Dashboard</a>
                <a href="{{ route('products.index') }}" class="block px-4 py-2 rounded hover:bg-amber-50 text-gray-700">Inventory</a>
            @endif
            
            <a href="{{ route('pos.index') }}" class="block px-4 py-2 rounded bg-amber-100 text-amber-900 font-bold">Point of Sale</a>
            
            <form method="POST" action="/logout" class="mt-10">
                @csrf
                <button class="w-full text-left px-4 py-2 text-red-500 hover:bg-red-50">Logout</button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="ml-64 w-full p-8">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>