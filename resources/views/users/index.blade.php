<x-app-layout>
    <div class="space-y-6">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">User Management</h2>
                <p class="text-sm text-gray-500">Create and manage cashier or admin accounts.</p>
            </div>
            
            <!-- Add User Form -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 w-full md:w-1/2">
                <h3 class="font-bold text-gray-800 mb-4">Create New Account</h3>
                
                <!-- Error Messages Section -->
                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-50 text-red-600 rounded-lg text-sm border border-red-100">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('users.store') }}" method="POST" class="space-y-3">
                    @csrf
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Role</label>
                            <select name="role" class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                                <option value="cashier">Cashier</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-bold text-gray-500 uppercase">Password</label>
                            <input type="password" name="password" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Confirm Password</label>
                        <input type="password" name="password_confirmation" required class="w-full rounded-lg border-gray-300 text-sm focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    
                    <button type="submit" class="w-full bg-gray-900 text-white py-2 rounded-lg font-bold hover:bg-gray-800 transition">
                        Create Account
                    </button>
                </form>
            </div>
        </div>

        <!-- Users List Table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-4 border-b border-gray-100 bg-gray-50">
                <h3 class="font-bold text-gray-700">Existing Accounts</h3>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white text-gray-500 text-xs uppercase border-b border-gray-100">
                        <th class="p-4">Name</th>
                        <th class="p-4">Email</th>
                        <th class="p-4">Role</th>
                        <th class="p-4">Joined</th>
                        <th class="p-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="p-4 font-bold text-gray-800">{{ $user->name }}</td>
                        <td class="p-4 text-gray-600">{{ $user->email }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded text-xs font-bold uppercase {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td class="p-4 text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="p-4 text-right">
                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user permanently?');">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                            </form>
                            @else
                                <span class="text-gray-400 italic">Current User</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>