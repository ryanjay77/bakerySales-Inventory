<x-app-layout>
    <div class="space-y-8">
        
        <!-- Header Section -->
        <div>
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">User Management</h2>
            <p class="text-gray-500 mt-2 text-lg">Manage access for administrators and cashiers.</p>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 items-start">
            
            <!-- 1. CREATE ACCOUNT FORM (Left/Top) -->
            <div class="xl:col-span-1">
                <div class="bg-white p-8 rounded-3xl shadow-xl border border-gray-100 sticky top-6">
                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-4">
                        <div class="p-2 bg-amber-100 text-amber-600 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">Create Account</h3>
                    </div>
                    
                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-red-50 text-red-700 rounded-xl border border-red-200 text-sm">
                            <div class="font-bold mb-1">Please fix the following:</div>
                            <ul class="list-disc list-inside space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <!-- Name Input -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Full Name</label>
                            <input type="text" name="name" value="{{ old('name') }}" required 
                                   class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all shadow-sm"
                                   placeholder="e.g. Juan dela Cruz">
                        </div>

                        <!-- Email Input -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Email Address</label>
                            <input type="email" name="email" value="{{ old('email') }}" required 
                                   class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all shadow-sm"
                                   placeholder="user@bakery.com">
                        </div>

                        <!-- Role Selection -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Role Assignment</label>
                            <div class="relative">
                                <select name="role" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 appearance-none transition-all shadow-sm cursor-pointer">
                                    <option value="cashier">Cashier (POS Only)</option>
                                    <option value="admin">Administrator (Full Access)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Password Inputs Grid -->
                        <div class="grid grid-cols-1 gap-5">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Password</label>
                                <input type="password" name="password" required 
                                       class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all shadow-sm"
                                       placeholder="••••••••">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 uppercase tracking-wide mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation" required 
                                       class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-300 text-gray-900 focus:bg-white focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all shadow-sm"
                                       placeholder="••••••••">
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 text-white font-bold text-lg py-4 rounded-xl shadow-lg hover:shadow-xl transition-all transform active:scale-[0.98] flex items-center justify-center gap-2 mt-4">
                            <span>Create Account</span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </button>
                    </form>
                </div>
            </div>

            <!-- 2. USERS LIST TABLE (Right/Bottom) -->
            <div class="xl:col-span-2">
                <div class="bg-white rounded-3xl shadow-lg border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                        <h3 class="font-bold text-gray-800 text-lg">Existing Accounts</h3>
                        <span class="bg-white px-3 py-1 rounded-full text-xs font-bold text-gray-500 border border-gray-200">{{ $users->count() }} Users</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-white text-gray-400 text-xs uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4 font-bold">User Details</th>
                                    <th class="px-6 py-4 font-bold text-center">Role</th>
                                    <th class="px-6 py-4 font-bold text-right">Joined Date</th>
                                    <th class="px-6 py-4 font-bold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($users as $user)
                                <tr class="hover:bg-amber-50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 font-bold text-sm border border-gray-200">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                            {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-blue-100 text-blue-700 border border-blue-200' }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-gray-500 font-medium">
                                        {{ $user->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        @if($user->id !== auth()->id())
                                        <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-400 hover:text-red-600 font-bold text-sm p-2 rounded-lg hover:bg-red-50 transition-colors" title="Delete User">
                                                Delete
                                            </button>
                                        </form>
                                        @else
                                            <span class="text-xs font-bold text-gray-400 bg-gray-100 px-2 py-1 rounded">You</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>