@extends('admin.layout')

@section('title', 'Transaction Details')
@section('page-title', 'Transaction Details')
@section('page-description', 'View transaction information')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Transaction Details Card -->
    <div class="bg-dark-900 rounded-xl p-6 border border-dark-800">
        <div class="flex justify-between items-start mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-100">Transaction #{{ $transaction->id }}</h3>
                <p class="text-sm text-gray-400">Created {{ $transaction->created_at->format('M d, Y \a\t g:i A') }}</p>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('admin.transactions.edit', $transaction) }}" 
                   class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors">
                    Edit Transaction
                </a>
                <a href="{{ route('admin.transactions.index') }}" 
                   class="px-4 py-2 bg-dark-800 hover:bg-dark-700 text-gray-300 rounded-lg transition-colors">
                    Back to List
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Transaction Information -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-200 border-b border-dark-800 pb-2">Transaction Information</h4>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-400">Type</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($transaction->type === 'deposit') bg-green-100 text-green-800
                            @elseif($transaction->type === 'withdrawal') bg-red-100 text-red-800
                            @else bg-blue-100 text-blue-800
                            @endif">
                            {{ ucfirst($transaction->type) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400">Amount</label>
                        <p class="text-lg font-semibold text-gray-100">${{ number_format($transaction->amount_in_dollars, 2) }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400">Status</label>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($transaction->status === 'completed') bg-green-100 text-green-800
                            @elseif($transaction->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($transaction->status === 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($transaction->status) }}
                        </span>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400">Description</label>
                        <p class="text-gray-100">{{ $transaction->description }}</p>
                    </div>
                </div>
            </div>

            <!-- User Information -->
            <div class="space-y-4">
                <h4 class="text-md font-medium text-gray-200 border-b border-dark-800 pb-2">User Information</h4>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-400">Name</label>
                        <p class="text-gray-100">{{ $transaction->user->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400">Email</label>
                        <p class="text-gray-100">{{ $transaction->user->email }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400">User ID</label>
                        <p class="text-gray-100">#{{ $transaction->user->id }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-400">Account Balance</label>
                        <p class="text-gray-100">${{ number_format(($transaction->user->balance ?? 0) / 100, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="mt-6 pt-6 border-t border-dark-800">
            <h4 class="text-md font-medium text-gray-200 mb-4">Timestamps</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-400">Created At</label>
                    <p class="text-gray-100">{{ $transaction->created_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400">Updated At</label>
                    <p class="text-gray-100">{{ $transaction->updated_at->format('M d, Y \a\t g:i A') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Card -->
    <div class="bg-dark-900 rounded-xl p-6 border border-dark-800">
        <h4 class="text-md font-medium text-gray-200 mb-4">Actions</h4>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.transactions.edit', $transaction) }}" 
               class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors">
                Edit Transaction
            </a>
            <a href="{{ route('admin.users.show', $transaction->user) }}" 
               class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                View User Profile
            </a>
            <form method="POST" action="{{ route('admin.transactions.destroy', $transaction) }}" 
                  class="inline" 
                  onsubmit="return confirm('Are you sure you want to delete this transaction? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Delete Transaction
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
