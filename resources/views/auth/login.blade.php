<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pan de Pane</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-white">

    <div class="flex min-h-screen w-full">
        
        <!-- LEFT SIDE: Image & Branding -->
        <!-- Hidden on mobile, visible on large screens -->
        <div class="hidden lg:flex w-1/2 bg-cover bg-center relative" 
             style="background-image: url('https://images.unsplash.com/photo-1509440159596-0249088772ff?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');">
            <!-- Dark Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-50"></div>
            
            <div class="relative z-10 w-full h-full flex flex-col justify-center px-12 text-white">
                <h1 class="text-5xl font-bold mb-4 tracking-tight">Pan de Pane<br>Bakeshoppe</h1>
                <p class="text-xl text-amber-200 opacity-90 max-w-md">
                    Experience the taste of home. Manage your inventory and sales with ease.
                </p>
            </div>
        </div>

        <!-- RIGHT SIDE: Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
            <div class="w-full max-w-md bg-white p-8 rounded-2xl shadow-xl border border-gray-100">
                
                <!-- Logo / Icon -->
                <div class="text-center mb-8">
                    <div class="mx-auto h-16 w-16 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-800">Welcome Back!</h2>
                    <p class="text-sm text-gray-500 mt-2">Please sign in to your account</p>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 border border-red-200 text-red-600 rounded-lg text-sm">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all"
                               placeholder="admin@bakery.com">
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="password" type="password" name="password" required 
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none transition-all"
                               placeholder="••••••••">
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-gray-300 text-amber-600 shadow-sm focus:ring-amber-500">
                            <span class="ml-2 text-sm text-gray-600">Remember me</span>
                        </label>
                        
                        @if (Route::has('password.request'))
                            <a class="text-sm text-amber-600 hover:text-amber-700 font-medium" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold py-3 rounded-lg shadow-md hover:shadow-lg transition-all transform active:scale-95">
                        Sign In
                    </button>
                </form>

                <p class="mt-8 text-center text-xs text-gray-400">
                    &copy; {{ date('Y') }} Pan de Pane Bakeshoppe Inventory System
                </p>
            </div>
        </div>
    </div>

</body>
</html>