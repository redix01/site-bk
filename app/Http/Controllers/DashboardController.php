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
            'currency' => 'USD',
            'status' => 'active',
        ]);
        
        // Get recent transactions (last 5)
        $recentTransactions = Transaction::where('user_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->with(['user', 'recipient'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
        
        // Get transaction statistics for each period filter
        $stats = [
            '7d' => $this->statsForPeriod($user->id, now()->subDays(7)),
            '30d' => $this->statsForPeriod($user->id, now()->subDays(30)),
            '1y' => $this->statsForPeriod($user->id, now()->subYear()),
        ];

        return inertia('Dashboard', [
            'user' => $user,
            'wallet' => $wallet,
            'recentTransactions' => $recentTransactions,
            'stats' => $stats,
        ]);
    }

    /**
     * Sum a user's completed transactions of a given type since a given date.
     */
    private function sumTransactions(int $userId, string $column, string $type, \Illuminate\Support\Carbon $since): int
    {
        return (int) Transaction::where($column, $userId)
            ->where('type', $type)
            ->where('status', 'completed')
            ->where('created_at', '>=', $since)
            ->sum('amount');
    }

    /**
     * Build the deposit/withdrawal/sent/received totals for a user within a given period.
     */
    private function statsForPeriod(int $userId, \Illuminate\Support\Carbon $since): array
    {
        $transactionCount = Transaction::where('status', 'completed')
            ->where('created_at', '>=', $since)
            ->where(function ($query) use ($userId) {
                $query->where('user_id', $userId)->orWhere('recipient_id', $userId);
            })
            ->count();

        return [
            'total_deposits' => $this->sumTransactions($userId, 'user_id', 'deposit', $since),
            'total_withdrawals' => $this->sumTransactions($userId, 'user_id', 'withdrawal', $since),
            'total_transfers_sent' => $this->sumTransactions($userId, 'user_id', 'transfer', $since),
            'total_transfers_received' => $this->sumTransactions($userId, 'recipient_id', 'transfer', $since),
            'transaction_count' => $transactionCount,
        ];
    }
}
