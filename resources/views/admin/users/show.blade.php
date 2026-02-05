@extends('admin.layout')

@section('title', 'User Details')
@section('page-title', 'User Details')
@section('page-description', 'View user information and activity')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- User Header -->
    <div class="bg-dark-800 rounded-xl p-6 border border-dark-700">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-6">
                <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                    <span class="text-white text-2xl font-bold">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-white">{{ $user->name }}</h2>
                    <p class="text-gray-400">{{ $user->email }}</p>
                    <div class="flex items-center space-x-2 mt-2">
                        @if($user->is_admin)
                            <span class="px-2 py-1 bg-primary-600 text-white text-xs rounded-full">Admin</span>
                        @else
                            <span class="px-2 py-1 bg-gray-600 text-white text-xs rounded-full">User</span>
                        @endif
                        <span class="px-2 py-1 bg-green-600 text-white text-xs rounded-full">Active</span>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Edit</span>
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="px-4 py-2 bg-dark-700 hover:bg-dark-600 text-white rounded-lg transition-colors">
                    Back to Users
                </a>
            </div>
        </div>
    </div>

    <!-- User Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="bg-dark-800 rounded-xl p-6 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-6">Personal Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white">{{ $user->name }}</div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white">{{ $user->email }}</div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white">{{ $user->phone ?? 'Not provided' }}</div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Account Number</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white">{{ $user->account_number ?? 'Not assigned' }}</div>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-dark-800 rounded-xl p-6 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-6">Account Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Account Balance</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white text-xl font-semibold">${{ number_format(($user->balance ?? 0) / 100, 2) }}</div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Account Type</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white">{{ ucfirst($user->account_type ?? 'Standard') }}</div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Account Status</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white">{{ ucfirst($user->status ?? 'Active') }}</div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Member Since</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white">{{ $user->created_at->format('M d, Y') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-dark-800 rounded-xl p-6 border border-dark-700">
        <h3 class="text-lg font-semibold text-white mb-6">Recent Transactions</h3>
        
        <div class="space-y-4">
            @forelse($user->transactions()->latest()->limit(10)->get() as $transaction)
            <div class="flex items-center justify-between p-4 bg-dark-700 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-medium">{{ $transaction->description }}</p>
                        <p class="text-gray-400 text-sm">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-white font-semibold">${{ number_format($transaction->amount, 2) }}</p>
                    <p class="text-gray-400 text-sm">{{ ucfirst($transaction->status) }}</p>
                </div>
            </div>
            @empty
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                <p class="text-gray-400">No transactions found</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
