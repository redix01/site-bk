<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_transactions' => \App\Models\Transaction::count(),
            'total_balance' => \App\Models\Wallet::sum('balance'),
            'recent_activity' => \App\Models\Transaction::with('user')
                ->latest()
                ->limit(5)
                ->get()
                ->map(function ($transaction) {
                    return [
                        'description' => $transaction->description,
                        'amount' => $transaction->amount_in_dollars,
                        'date' => $transaction->created_at->format('M d, Y'),
                        'status' => ucfirst($transaction->status),
                    ];
                })
        ];

        return inertia('Admin/Dashboard', compact('stats'));
    }

    /**
     * Show the admin profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        return inertia('Admin/Profile');
    }
}
