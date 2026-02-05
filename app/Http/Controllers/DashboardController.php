<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use App\Models\Wallet;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // If admin, redirect to admin dashboard (unless explicitly viewing client dashboard)
        if ($user->isAdmin() && !$request->has('view')) {
            return redirect()->route('admin.dashboard');
        }
        
        // Get or create wallet for user
        $wallet = $user->wallet ?? Wallet::create([
            'user_id' => $user->id,
            'account_number' => Wallet::generateAccountNumber(),
            'balance' => 0,
            'ledger_balance' => 0,
            'currency' => $user->preferred_currency ?: 'USD',
            'status' => 'active',
        ]);
        
        // Get recent transactions (last 5)
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->with(['user', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get transaction statistics
        $stats = [
            'total_deposits' => Transaction::where('user_id', $user->id)
                ->where('type', 'deposit')
                ->where('status', 'completed')
                ->sum('amount'),
            'total_withdrawals' => Transaction::where('user_id', $user->id)
                ->where('type', 'withdrawal')
                ->where('status', 'completed')
                ->sum('amount'),
            'total_transfers_sent' => Transaction::where('user_id', $user->id)
                ->where('type', 'transfer')
                ->where('status', 'completed')
                ->sum('amount'),
            'total_transfers_received' => Transaction::where('recipient_id', $user->id)
                ->where('type', 'transfer')
                ->where('status', 'completed')
                ->sum('amount'),
        ];
        
        return inertia('Dashboard', [
            'user' => $user,
            'wallet' => $wallet,
            'recentTransactions' => $recentTransactions,
            'stats' => $stats,
        ]);
    }
}
