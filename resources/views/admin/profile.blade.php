@extends('admin.layout')

@section('title', 'Profile')
@section('page-title', 'Profile')
@section('page-description', 'Manage your admin account settings')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Profile Header -->
    <div class="bg-dark-800 rounded-xl p-6 border border-dark-700">
        <div class="flex items-center space-x-6">
            <div class="w-20 h-20 bg-gradient-to-r from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                <span class="text-white text-2xl font-bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-white">{{ auth()->user()->name }}</h2>
                <p class="text-gray-400">{{ auth()->user()->email }}</p>
                <div class="flex items-center space-x-2 mt-2">
                    <span class="px-2 py-1 bg-primary-600 text-white text-xs rounded-full">Admin</span>
                    <span class="px-2 py-1 bg-green-600 text-white text-xs rounded-full">Active</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Profile Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Personal Information -->
        <div class="bg-dark-800 rounded-xl p-6 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-6">Personal Information</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white">{{ auth()->user()->name }}</div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white">{{ auth()->user()->email }}</div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Phone Number</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white">{{ auth()->user()->phone ?? 'Not provided' }}</div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Account Balance</label>
                    <div class="p-3 bg-dark-700 rounded-lg text-white">${{ number_format((auth()->user()->balance ?? 0) / 100, 2) }}</div>
                </div>
            </div>
        </div>

        <!-- Account Statistics -->
        <div class="bg-dark-800 rounded-xl p-6 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-6">Account Statistics</h3>
            
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-dark-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Total Transactions</p>
                            <p class="text-gray-400 text-sm">All time</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-white font-semibold">{{ auth()->user()->transactions()->count() }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-dark-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Account Created</p>
                            <p class="text-gray-400 text-sm">Member since</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-white font-semibold">{{ auth()->user()->created_at->format('M Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-dark-700 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-white font-medium">Last Login</p>
                            <p class="text-gray-400 text-sm">Recent activity</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-white font-semibold">{{ auth()->user()->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="bg-dark-800 rounded-xl p-6 border border-dark-700">
        <h3 class="text-lg font-semibold text-white mb-6">Security Settings</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <h4 class="text-md font-medium text-white">Password</h4>
                <p class="text-gray-400 text-sm">Last changed: Never</p>
                <button class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                    Change Password
                </button>
            </div>
            
            <div class="space-y-4">
                <h4 class="text-md font-medium text-white">Two-Factor Authentication</h4>
                <p class="text-gray-400 text-sm">Add an extra layer of security</p>
                <button class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                    Enable 2FA
                </button>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-dark-800 rounded-xl p-6 border border-dark-700">
        <h3 class="text-lg font-semibold text-white mb-6">Recent Activity</h3>
        
        <div class="space-y-4">
            @forelse(auth()->user()->transactions()->latest()->limit(5)->get() as $transaction)
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
                <p class="text-gray-400">No recent activity</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
